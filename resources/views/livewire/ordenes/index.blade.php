<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title">Órdenes</h1>
                    <p class="subtitle">Organiza productos y cantidades por orden.</p>
                </div>
            </div>
            <div class="level-right">
                <a class="button is-light" href="{{ route('productos.index') }}">
                    Productos
                </a>
                @if ($isCreatePage)
                    <a class="button" href="{{ route('ordenes.index') }}">Volver a órdenes</a>
                @elseif ($productosDisponibles->isEmpty())
                    <button class="button is-primary" type="button" disabled>
                        Nueva orden
                    </button>
                @else
                    <a class="button is-primary" href="{{ route('ordenes.create') }}">
                        Nueva orden
                    </a>
                @endif
            </div>
        </div>

        @if ($productosDisponibles->isEmpty())
            <div class="notification is-warning is-light">
                Registra productos antes de crear una orden.
                <a href="{{ route('productos.index') }}">Ir a productos</a>
            </div>
        @endif

        @if (session()->has('status'))
            <div class="notification is-success is-light" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($formVisible)
            <div class="box">
                <h2 class="title is-4">{{ $ordenId ? 'Editar orden' : 'Nueva orden' }}</h2>

                <form wire:submit="save">
                    @error('items') <p class="help is-danger mb-3">{{ $message }}</p> @enderror

                    @foreach ($items as $index => $item)
                        <div class="columns is-align-items-end" wire:key="orden-item-{{ $index }}">
                            <div class="column is-7">
                                <label class="label" for="producto-{{ $index }}">Producto</label>
                                <div class="select is-fullwidth @error('items.'.$index.'.producto_id') is-danger @enderror">
                                    <select id="producto-{{ $index }}" wire:model="items.{{ $index }}.producto_id" required>
                                        <option value="">Selecciona un producto</option>
                                        @foreach ($productosDisponibles as $producto)
                                            <option value="{{ $producto->id }}">
                                                {{ $producto->nombre }} ({{ $producto->SKU }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('items.'.$index.'.producto_id') <p class="help is-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="column is-3">
                                <label class="label" for="cantidad-{{ $index }}">Cantidad</label>
                                <input class="input @error('items.'.$index.'.cantidad') is-danger @enderror" id="cantidad-{{ $index }}" type="number" wire:model="items.{{ $index }}.cantidad" min="1" step="1" required>
                                @error('items.'.$index.'.cantidad') <p class="help is-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="column is-2">
                                <button class="button is-danger is-light is-fullwidth" type="button" wire:click="removeItem({{ $index }})" @disabled(count($items) === 1)>
                                    Quitar
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <div class="buttons">
                        <button class="button is-link is-light" type="button" wire:click="addItem">
                            Agregar producto
                        </button>
                        <button class="button is-primary" type="submit" wire:loading.attr="disabled" wire:target="save">
                            Guardar orden
                        </button>
                        <button class="button" type="button" wire:click="cancelForm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @unless ($isCreatePage)
            <div class="field">
                <label class="label" for="buscar">Buscar órdenes</label>
                <div class="control">
                    <input class="input" id="buscar" type="search" wire:model.live.debounce.300ms="search" placeholder="Número de orden, producto o SKU">
                </div>
            </div>

            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Fecha</th>
                            <th>Productos y cantidades</th>
                            <th class="has-text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ordenes as $orden)
                            <tr wire:key="orden-{{ $orden->id }}">
                                <td>#{{ $orden->id }}</td>
                                <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @foreach ($orden->productos as $producto)
                                        <div>{{ $producto->nombre }} × {{ $producto->pivot->cantidad }}</div>
                                    @endforeach
                                </td>
                                <td class="has-text-right">
                                    <div class="buttons are-small is-right">
                                        <button class="button is-info is-light" type="button" wire:click="startEditing({{ $orden->id }})">
                                            Editar
                                        </button>
                                        <button class="button is-danger is-light" type="button" wire:click="delete({{ $orden->id }})" wire:confirm="¿Eliminar esta orden? Esta acción no se puede deshacer.">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="has-text-centered has-text-grey">
                                    {{ $search !== '' ? 'No se encontraron órdenes.' : 'Todavía no hay órdenes registradas.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ordenes->hasPages())
                <nav class="pagination is-centered" role="navigation" aria-label="Paginación de órdenes">
                    <button class="pagination-previous" type="button" wire:click="previousPage" @disabled($ordenes->onFirstPage())>
                        Anterior
                    </button>
                    <button class="pagination-next" type="button" wire:click="nextPage" @disabled(!$ordenes->hasMorePages())>
                        Siguiente
                    </button>
                    <p class="has-text-centered" aria-live="polite">
                        Página {{ $ordenes->currentPage() }} de {{ $ordenes->lastPage() }}
                    </p>
                </nav>
            @endif
        @endunless
    </div>
</section>
