<?php

namespace Tests\Feature;

use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_export_returns_an_inline_a5_pdf_with_a_qr_code(): void
    {
        $producto = Producto::factory()->create();

        $response = $this->get(route('productos.pdf', $producto));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename="producto-'.$producto->id.'.pdf"');

        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_order_export_returns_an_inline_a4_pdf_with_its_product_list_and_qr_code(): void
    {
        $producto = Producto::factory()->create([
            'nombre' => 'Monitor',
            'SKU' => 'MON-001',
            'Proveedor' => 'Proveedor Central',
        ]);
        $orden = Orden::create();
        $orden->productos()->attach($producto->id, ['cantidad' => 3]);

        $response = $this->get(route('ordenes.pdf', $orden));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename="orden-'.$orden->id.'.pdf"');

        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }
}
