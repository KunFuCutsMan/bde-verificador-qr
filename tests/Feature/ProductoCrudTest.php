<?php

namespace Tests\Feature;

use App\Livewire\Productos\Index;
use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_page_shows_product_fields_and_related_order_quantities(): void
    {
        $producto = Producto::factory()->create([
            'nombre' => 'Monitor',
            'SKU' => 'MON-001',
            'Proveedor' => 'Proveedor Central',
            'cantidad' => 8,
        ]);
        $orden = Orden::create();
        $orden->productos()->attach($producto->id, ['cantidad' => 3]);

        $this->get(route('productos.show', $producto))
            ->assertOk()
            ->assertSee('Monitor')
            ->assertSee('MON-001')
            ->assertSee('Proveedor Central')
            ->assertSee('Cantidad solicitada: 3')
            ->assertSee(route('ordenes.show', $orden));
    }

    public function test_products_page_lists_products(): void
    {
        Producto::factory()->create([
            'nombre' => 'Teclado mecánico',
            'SKU' => 'TEC-001',
            'Proveedor' => 'Proveedor Central',
        ]);

        $this->get(route('productos.index'))
            ->assertOk()
            ->assertSee('Teclado mecánico')
            ->assertSee('TEC-001')
            ->assertSee('Proveedor Central');
    }

    public function test_product_can_be_created_updated_and_deleted(): void
    {
        Livewire::test(Index::class)
            ->call('startCreating')
            ->set('nombre', 'Mouse inalámbrico')
            ->set('SKU', 'MOU-001')
            ->set('Proveedor', 'Distribuidora Norte')
            ->set('cantidad', '12')
            ->call('save')
            ->assertHasNoErrors();

        $producto = Producto::query()->where('SKU', 'MOU-001')->firstOrFail();

        $this->assertSame('Mouse inalámbrico', $producto->nombre);
        $this->assertSame(12, $producto->cantidad);

        Livewire::test(Index::class)
            ->call('startEditing', $producto->id)
            ->set('nombre', 'Mouse inalámbrico nuevo')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Mouse inalámbrico nuevo',
        ]);

        Livewire::test(Index::class)
            ->call('delete', $producto->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }

    public function test_product_form_validates_required_fields_and_non_negative_quantity(): void
    {
        Livewire::test(Index::class)
            ->call('startCreating')
            ->set('nombre', '')
            ->set('SKU', '')
            ->set('Proveedor', '')
            ->set('cantidad', '-1')
            ->call('save')
            ->assertHasErrors([
                'nombre' => 'required',
                'SKU' => 'required',
                'Proveedor' => 'required',
                'cantidad' => 'min',
            ]);

        $this->assertDatabaseCount('productos', 0);
    }

    public function test_search_filters_products_and_resets_pagination(): void
    {
        Producto::factory()->create(['nombre' => 'Coincidencia visible']);
        Producto::factory()->create(['nombre' => 'Otro producto']);

        Livewire::test(Index::class)
            ->set('search', 'Coincidencia')
            ->assertSee('Coincidencia visible')
            ->assertDontSee('Otro producto');
    }

    public function test_product_list_is_paginated(): void
    {
        foreach (range(0, 10) as $number) {
            Producto::factory()->create([
                'nombre' => sprintf('Producto %02d', $number),
            ]);
        }

        $oldestProductId = Producto::query()->oldest('id')->firstOrFail()->id;

        Livewire::test(Index::class)
            ->assertSee('Página 1 de 2')
            ->call('nextPage')
            ->assertSee('Página 2 de 2')
            ->call('delete', $oldestProductId)
            ->assertSee('Producto 10')
            ->assertDontSee('Producto 00');
    }
}
