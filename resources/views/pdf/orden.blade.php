<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 14mm;
        }

        body {
            color: #202b38;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
        }

        h1 {
            color: #173b63;
            font-size: 20pt;
            margin: 0 0 6pt;
        }

        .meta {
            color: #536579;
            margin-bottom: 16pt;
        }

        .content,
        .products {
            border-collapse: collapse;
            width: 100%;
        }

        .qr {
            text-align: right;
            vertical-align: top;
            width: 150pt;
        }

        .qr img {
            height: 110pt;
            width: 110pt;
        }

        .products {
            margin-top: 14pt;
        }

        .products th,
        .products td {
            border: 1px solid #cbd5df;
            padding: 7pt;
            text-align: left;
        }

        .products th {
            background: #eaf0f6;
            color: #173b63;
        }

        .number {
            text-align: right !important;
        }

        .printed {
            color: #536579;
            font-size: 9pt;
            margin-top: 20pt;
            text-align: right;
        }
    </style>
</head>
<body>
    <table class="content">
        <tr>
            <td>
                <h1>Orden #{{ $orden->id }}</h1>
                <div class="meta">
                    Creada: {{ $orden->created_at->format('d/m/Y H:i') }}<br>
                    Última actualización: {{ $orden->updated_at->format('d/m/Y H:i') }}
                </div>
            </td>
            <td class="qr">
                <img src="{{ $qrCodeDataUri }}" alt="Código QR de la orden">
                <div>Escanea para ver la orden</div>
            </td>
        </tr>
    </table>

    <table class="products">
        <thead>
            <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Proveedor</th>
                <th class="number">Cantidad solicitada</th>
                <th class="number">Cantidad disponible</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orden->productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->SKU }}</td>
                    <td>{{ $producto->Proveedor }}</td>
                    <td class="number">{{ $producto->pivot->cantidad }}</td>
                    <td class="number">{{ $producto->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="printed">Fecha de impresión: {{ $printedAt->format('d/m/Y H:i') }}</p>
</body>
</html>
