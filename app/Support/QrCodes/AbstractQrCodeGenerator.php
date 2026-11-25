<?php

namespace App\Support\QrCodes;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WriterInterface;

abstract class AbstractQrCodeGenerator
{
    protected function generateDataUri(string $url, WriterInterface $writer): string
    {
        $result = (new Builder(
            writer: $writer,
            data: $url,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
        ))->build();

        return $result->getDataUri();
    }

    protected function generateSvgDataUri(string $url): string
    {
        return $this->generateDataUri($url, new SvgWriter);
    }

    protected function generatePngDataUri(string $url): string
    {
        return $this->generateDataUri($url, new PngWriter);
    }
}
