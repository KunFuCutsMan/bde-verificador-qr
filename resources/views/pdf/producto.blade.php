<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A5 portrait;
            margin: 12mm;
        }

        body {
            color: #202b38;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
        }

        h1 {
            border-bottom: 2px solid #2b6cb0;
            color: #173b63;
            font-size: 20pt;
            margin: 0 0 18pt;
            padding-bottom: 8pt;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .content td {
            vertical-align: top;
        }

        .details {
            padding-right: 12pt;
        }

        .details th,
        .details td {
            border-bottom: 1px solid #d8e0e8;
            padding: 8pt 4pt;
            text-align: left;
        }

        .details th {
            color: #536579;
            font-weight: normal;
            width: 42%;
        }

        .qr {
            text-align: center;
            width: 34%;
        }

        .qr img {
            height: 130pt;
            width: 130pt;
        }

        .printed {
            color: #536579;
            font-size: 9pt;
            margin-top: 24pt;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Etiqueta de producto</h1>

    <table class="content">
        <tr>
            <td class="details">
                <table>
                    <tr><th>ID</th><td>{{ $producto->id }}</td></tr>
                    <tr><th>Nombre</th><td>{{ $producto->nombre }}</td></tr>
                    <tr><th>SKU</th><td>{{ $producto->SKU }}</td></tr>
                    <tr><th>Proveedor</th><td>{{ $producto->Proveedor }}</td></tr>
                    <tr><th>Cantidad disponible</th><td>{{ $producto->cantidad }}</td></tr>
                    <tr><th>Creado</th><td>{{ $producto->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Actualizado</th><td>{{ $producto->updated_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </td>
            <td class="qr">
                <img src="{{ $qrCodeDataUri }}" alt="Código QR del producto">
                <p>Escanea para ver el producto</p>
            </td>
        </tr>
    </table>

    <p class="printed">Fecha de impresión de etiqueta: {{ $printedAt->format('d/m/Y H:i') }}</p>
</body>
</html>
