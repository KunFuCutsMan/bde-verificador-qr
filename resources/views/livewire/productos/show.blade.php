<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title">Detalle del producto</h1>
                    <p class="subtitle">{{ $producto->nombre }}</p>
                </div>
            </div>
            <div class="level-right">
                <a class="button" href="{{ route('productos.index') }}">Volver a productos</a>
            </div>
        </div>

        <div class="box">
            <dl class="columns is-multiline">
                <div class="column is-half">
                    <dt class="has-text-grey">ID</dt>
                    <dd class="title is-5">{{ $producto->id }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">Nombre</dt>
                    <dd class="title is-5">{{ $producto->nombre }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">SKU</dt>
                    <dd class="title is-5">{{ $producto->SKU }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">Proveedor</dt>
                    <dd class="title is-5">{{ $producto->Proveedor }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">Cantidad disponible</dt>
                    <dd class="title is-5">{{ $producto->cantidad }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">Creado</dt>
                    <dd>{{ $producto->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="column is-half">
                    <dt class="has-text-grey">Última actualización</dt>
                    <dd>{{ $producto->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <h2 class="title is-4">Órdenes relacionadas</h2>
        <div class="box">
            @forelse ($producto->ordenes as $orden)
                <div class="level">
                    <div class="level-left">
                        <a href="{{ route('ordenes.show', $orden) }}">Orden #{{ $orden->id }}</a>
                    </div>
                    <div class="level-right">
                        <span>Cantidad solicitada: {{ $orden->pivot->cantidad }}</span>
                    </div>
                </div>
            @empty
                <p class="has-text-grey">Este producto todavía no aparece en ninguna orden.</p>
            @endforelse
        </div>
    </div>
</section>
