<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Producto;
use App\Support\QrCodes\OrdenQrCodeGenerator;
use App\Support\QrCodes\ProductoQrCodeGenerator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;

class PdfExportController extends Controller
{
    public function producto(Producto $producto): Response
    {
        return $this->renderPdf(
            view('pdf.producto', [
                'producto' => $producto,
                'printedAt' => now(),
                'qrCodeDataUri' => (new ProductoQrCodeGenerator)->generatePng($producto),
            ])->render(),
            'A5',
            'landscape',
            'producto-' . $producto->id . '.pdf',
        );
    }

    public function orden(Orden $orden): Response
    {
        $orden->load('productos');

        return $this->renderPdf(
            view('pdf.orden', [
                'orden' => $orden,
                'printedAt' => now(),
                'qrCodeDataUri' => (new OrdenQrCodeGenerator)->generatePng($orden),
            ])->render(),
            'A4',
            'landscape',
            'orden-' . $orden->id . '.pdf',
        );
    }

    private function renderPdf(
        string $html,
        string $paper,
        string $orientation,
        string $filename,
    ): Response {
        $options = new Options;
        $options->set('isRemoteEnabled', false);

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper($paper, $orientation);
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
