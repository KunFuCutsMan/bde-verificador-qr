<?php

namespace App\Support\QrCodes;

use App\Models\Orden;
use InvalidArgumentException;

final class OrdenQrCodeGenerator extends AbstractQrCodeGenerator
{
    public function targetUrl(Orden $orden): string
    {
        if (! $orden->exists) {
            throw new InvalidArgumentException('Cannot generate a QR code for an unsaved order.');
        }

        return route('ordenes.show', $orden);
    }

    public function generate(Orden $orden): string
    {
        return $this->generateSvgDataUri($this->targetUrl($orden));
    }

    public function generatePng(Orden $orden): string
    {
        return $this->generatePngDataUri($this->targetUrl($orden));
    }
}
