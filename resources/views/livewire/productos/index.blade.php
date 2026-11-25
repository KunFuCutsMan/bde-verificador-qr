<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title">Productos</h1>
                    <p class="subtitle">Administra el inventario de productos.</p>
                </div>
            </div>
            <div class="level-right">
                <button class="button is-primary" type="button" wire:click="startCreating">
                    Nuevo producto
                </button>
            </div>
        </div>

        @if (session()->has('status'))
            <div class="notification is-success is-light" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($formVisible)
            <div class="box">
                <h2 class="title is-4">{{ $productoId ? 'Editar producto' : 'Nuevo producto' }}</h2>

                <form wire:submit="save">
                    <div class="columns is-multiline">
                        <div class="column is-half">
                            <label class="label" for="nombre">Nombre</label>
                            <div class="control">
                                <input class="input @error('nombre') is-danger @enderror" id="nombre" type="text" wire:model="nombre" maxlength="255" required>
                            </div>
                            @error('nombre') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="column is-half">
                            <label class="label" for="sku">SKU</label>
                            <div class="control">
                                <input class="input @error('SKU') is-danger @enderror" id="sku" type="text" wire:model="SKU" maxlength="255" required>
                            </div>
                            @error('SKU') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="column is-half">
                            <label class="label" for="proveedor">Proveedor</label>
                            <div class="control">
                                <input class="input @error('Proveedor') is-danger @enderror" id="proveedor" type="text" wire:model="Proveedor" maxlength="255" required>
                            </div>
                            @error('Proveedor') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="column is-half">
                            <label class="label" for="cantidad">Cantidad</label>
                            <div class="control">
                                <input class="input @error('cantidad') is-danger @enderror" id="cantidad" type="number" wire:model="cantidad" min="0" step="1" required>
                            </div>
                            @error('cantidad') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="buttons">
                        <button class="button is-primary" type="submit" wire:loading.attr="disabled" wire:target="save">
                            Guardar
                        </button>
                        <button class="button" type="button" wire:click="cancelForm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="field">
            <label class="label" for="buscar">Buscar productos</label>
            <div class="control">
                <input class="input" id="buscar" type="search" wire:model.live.debounce.300ms="search" placeholder="Nombre, SKU o proveedor">
            </div>
        </div>

        <div class="table-container">
            <table class="table is-fullwidth is-striped is-hoverable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Proveedor</th>
                        <th class="has-text-right">Cantidad</th>
                        <th class="has-text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr wire:key="producto-{{ $producto->id }}">
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->SKU }}</td>
                            <td>{{ $producto->Proveedor }}</td>
                            <td class="has-text-right">{{ $producto->cantidad }}</td>
                            <td class="has-text-right">
                                <div class="buttons are-small is-right">
                                    <button class="button is-info is-light" type="button" wire:click="startEditing({{ $producto->id }})">
                                        Editar
                                    </button>
                                    <button class="button is-danger is-light" type="button" wire:click="delete({{ $producto->id }})" wire:confirm="¿Eliminar este producto? Esta acción no se puede deshacer.">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="has-text-centered has-text-grey">
                                {{ $search !== '' ? 'No se encontraron productos.' : 'Todavía no hay productos registrados.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productos->hasPages())
            <nav class="pagination is-centered" role="navigation" aria-label="Paginación de productos">
                <button class="pagination-previous" type="button" wire:click="previousPage" @disabled($productos->onFirstPage())>
                    Anterior
                </button>
                <button class="pagination-next" type="button" wire:click="nextPage" @disabled(!$productos->hasMorePages())>
                    Siguiente
                </button>
                <p class="has-text-centered" aria-live="polite">
                    Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
                </p>
            </nav>
        @endif
    </div>
</section>
