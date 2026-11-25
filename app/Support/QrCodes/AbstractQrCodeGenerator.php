<?php

namespace App\Support\QrCodes;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\SvgWriter;

abstract class AbstractQrCodeGenerator
{
    protected function generateDataUri(string $url): string
    {
        $result = (new Builder(
            writer: new SvgWriter,
            data: $url,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
        ))->build();

        return $result->getDataUri();
    }
}
