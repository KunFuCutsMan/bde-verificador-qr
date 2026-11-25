<?php

namespace App\Support\QrCodes;

use App\Models\Producto;
use InvalidArgumentException;

final class ProductoQrCodeGenerator extends AbstractQrCodeGenerator
{
    public function targetUrl(Producto $producto): string
    {
        if (! $producto->exists) {
            throw new InvalidArgumentException('Cannot generate a QR code for an unsaved product.');
        }

        return route('productos.show', $producto);
    }

    public function generate(Producto $producto): string
    {
        return $this->generateDataUri($this->targetUrl($producto));
    }
}
