<?php

namespace Tests\Feature;

use App\Models\Orden;
use App\Models\Producto;
use App\Support\QrCodes\OrdenQrCodeGenerator;
use App\Support\QrCodes\ProductoQrCodeGenerator;
use InvalidArgumentException;
use Tests\TestCase;

class QrCodeGeneratorTest extends TestCase
{
    public function test_product_qr_code_targets_its_detail_page(): void
    {
        $producto = new Producto;
        $producto->setAttribute('id', 42);
        $producto->exists = true;

        $generator = new ProductoQrCodeGenerator;

        $this->assertSame(route('productos.show', $producto), $generator->targetUrl($producto));
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $generator->generate($producto));
    }

    public function test_order_qr_code_targets_its_detail_page(): void
    {
        $orden = new Orden;
        $orden->setAttribute('id', 27);
        $orden->exists = true;

        $generator = new OrdenQrCodeGenerator;

        $this->assertSame(route('ordenes.show', $orden), $generator->targetUrl($orden));
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $generator->generate($orden));
    }

    public function test_product_qr_code_rejects_an_unsaved_model(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot generate a QR code for an unsaved product.');

        (new ProductoQrCodeGenerator)->generate(new Producto);
    }

    public function test_order_qr_code_rejects_an_unsaved_model(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot generate a QR code for an unsaved order.');

        (new OrdenQrCodeGenerator)->generate(new Orden);
    }
}
