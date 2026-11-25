<?php

namespace Tests\Feature;

use App\Livewire\Ordenes\Index;
use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrdenCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_detail_page_shows_order_products_and_quantities(): void
    {
        $producto = Producto::factory()->create([
            'nombre' => 'Monitor',
            'SKU' => 'MON-001',
            'Proveedor' => 'Proveedor Central',
            'cantidad' => 8,
        ]);
        $orden = Orden::create();
        $orden->productos()->attach($producto->id, ['cantidad' => 3]);

        $this->get(route('ordenes.show', $orden))
            ->assertOk()
            ->assertSee('Detalle de la orden #'.$orden->id)
            ->assertSee('Monitor')
            ->assertSee('MON-001')
            ->assertSee('Cantidad solicitada')
            ->assertSee('3')
            ->assertSee(route('productos.show', $producto));
    }

    public function test_create_route_opens_order_form(): void
    {
        Producto::factory()->create([
            'nombre' => 'Monitor',
            'SKU' => 'MON-001',
        ]);

        $this->get(route('ordenes.create'))
            ->assertOk()
            ->assertSee('Guardar orden')
            ->assertSee('Selecciona un producto')
            ->assertDontSee('Productos y cantidades');
    }

    public function test_orders_page_lists_products_with_quantities(): void
    {
        $producto = Producto::factory()->create([
            'nombre' => 'Monitor',
            'SKU' => 'MON-001',
        ]);
        $orden = Orden::create();
        $orden->productos()->attach($producto->id, ['cantidad' => 3]);

        $this->get(route('ordenes.index'))
            ->assertOk()
            ->assertSee('#'.$orden->id)
            ->assertSee('Monitor × 3');
    }

    public function test_order_can_be_created_updated_and_deleted_with_multiple_products(): void
    {
        $monitor = Producto::factory()->create(['nombre' => 'Monitor']);
        $teclado = Producto::factory()->create(['nombre' => 'Teclado']);
        $mouse = Producto::factory()->create(['nombre' => 'Mouse']);

        Livewire::test(Index::class)
            ->call('startCreating')
            ->set('items', [
                ['producto_id' => (string) $monitor->id, 'cantidad' => '2'],
                ['producto_id' => (string) $teclado->id, 'cantidad' => '1'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $orden = Orden::with('productos')->firstOrFail();

        $this->assertCount(2, $orden->productos);
        $this->assertDatabaseHas('orden_producto', [
            'orden_id' => $orden->id,
            'producto_id' => $monitor->id,
            'cantidad' => 2,
        ]);

        Livewire::test(Index::class)
            ->call('startEditing', $orden->id)
            ->set('items', [
                ['producto_id' => (string) $mouse->id, 'cantidad' => '4'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('orden_producto', [
            'orden_id' => $orden->id,
            'producto_id' => $mouse->id,
            'cantidad' => 4,
        ]);
        $this->assertDatabaseMissing('orden_producto', [
            'orden_id' => $orden->id,
            'producto_id' => $monitor->id,
        ]);

        Livewire::test(Index::class)
            ->call('delete', $orden->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('ordenes', ['id' => $orden->id]);
        $this->assertDatabaseMissing('orden_producto', ['orden_id' => $orden->id]);
    }

    public function test_order_requires_at_least_one_unique_product_with_a_positive_quantity(): void
    {
        $producto = Producto::factory()->create();

        Livewire::test(Index::class)
            ->call('startCreating')
            ->set('items', [
                ['producto_id' => (string) $producto->id, 'cantidad' => '0'],
                ['producto_id' => (string) $producto->id, 'cantidad' => '2'],
            ])
            ->call('save')
            ->assertHasErrors([
                'items.0.cantidad' => 'min',
                'items.0.producto_id' => 'distinct',
                'items.1.producto_id' => 'distinct',
            ]);

        $this->assertDatabaseCount('ordenes', 0);

        Livewire::test(Index::class)
            ->call('startCreating')
            ->set('items', [])
            ->call('save')
            ->assertHasErrors(['items' => 'required']);
    }

    public function test_order_search_matches_product_name_and_sku(): void
    {
        $monitor = Producto::factory()->create([
            'nombre' => 'Monitor grande',
            'SKU' => 'MON-XL',
        ]);
        $teclado = Producto::factory()->create([
            'nombre' => 'Teclado compacto',
            'SKU' => 'TEC-S',
        ]);

        $ordenMonitor = Orden::create();
        $ordenMonitor->productos()->attach($monitor->id, ['cantidad' => 1]);
        $ordenTeclado = Orden::create();
        $ordenTeclado->productos()->attach($teclado->id, ['cantidad' => 1]);

        Livewire::test(Index::class)
            ->set('search', 'MON-XL')
            ->assertSee('#'.$ordenMonitor->id)
            ->assertDontSee('#'.$ordenTeclado->id);
    }
}
