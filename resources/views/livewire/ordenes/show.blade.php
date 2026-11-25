<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title">Detalle de la orden #{{ $orden->id }}</h1>
                    <p class="subtitle">Creada el {{ $orden->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="level-right">
                <a class="button" href="{{ route('ordenes.index') }}">Volver a órdenes</a>
            </div>
        </div>

        <div class="table-container">
            <table class="table is-fullwidth is-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Proveedor</th>
                        <th class="has-text-right">Cantidad solicitada</th>
                        <th class="has-text-right">Cantidad disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->productos as $producto)
                        <tr wire:key="orden-{{ $orden->id }}-producto-{{ $producto->id }}">
                            <td>
                                <a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a>
                            </td>
                            <td>{{ $producto->SKU }}</td>
                            <td>{{ $producto->Proveedor }}</td>
                            <td class="has-text-right">{{ $producto->pivot->cantidad }}</td>
                            <td class="has-text-right">{{ $producto->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
