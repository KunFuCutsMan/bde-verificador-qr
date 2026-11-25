<?php

namespace App\Livewire\Ordenes;

use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    private const PER_PAGE = 10;

    use WithPagination;

    public string $search = '';

    public bool $formVisible = false;

    public ?int $ordenId = null;

    public array $items = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function startCreating(): void
    {
        $this->resetForm();
        $this->items = [
            ['producto_id' => '', 'cantidad' => '1'],
        ];
        $this->formVisible = true;
    }

    public function startEditing(int $ordenId): void
    {
        $orden = Orden::with('productos')->findOrFail($ordenId);

        $this->ordenId = $orden->id;
        $this->items = $orden->productos
            ->map(fn (Producto $producto): array => [
                'producto_id' => (string) $producto->id,
                'cantidad' => (string) $producto->pivot->cantidad,
            ])
            ->all();
        $this->formVisible = true;

        $this->resetValidation();
    }

    public function addItem(): void
    {
        $this->items[] = ['producto_id' => '', 'cantidad' => '1'];
    }

    public function removeItem(int $index): void
    {
        if (array_key_exists($index, $this->items)) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'integer', 'distinct', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1', 'max:4294967295'],
        ]);

        $syncData = [];

        foreach ($validated['items'] as $item) {
            $syncData[(int) $item['producto_id']] = [
                'cantidad' => (int) $item['cantidad'],
            ];
        }

        $isCreating = $this->ordenId === null;

        DB::transaction(function () use ($syncData): void {
            $orden = $this->ordenId === null
                ? new Orden()
                : Orden::findOrFail($this->ordenId);

            $orden->save();
            $orden->productos()->sync($syncData);
        });

        session()->flash(
            'status',
            $isCreating ? 'Orden creada correctamente.' : 'Orden actualizada correctamente.',
        );

        $this->resetForm();
    }

    public function delete(int $ordenId): void
    {
        Orden::findOrFail($ordenId)->delete();
        $this->resetPage();

        session()->flash('status', 'Orden eliminada correctamente.');
    }

    public function render(): View
    {
        $search = $this->search;

        $ordenes = Orden::query()
            ->with('productos')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->whereHas('productos', function ($productosQuery) use ($search): void {
                        $productosQuery->where('nombre', 'like', '%'.$search.'%')
                            ->orWhere('SKU', 'like', '%'.$search.'%');
                    });

                    if (ctype_digit($search)) {
                        $query->orWhere('id', (int) $search);
                    }
                });
            })
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.ordenes.index', [
            'ordenes' => $ordenes,
            'productosDisponibles' => Producto::query()->orderBy('nombre')->get(),
        ])->layout('components.layout', [
            'title' => 'Órdenes',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'ordenId',
            'items',
            'formVisible',
        ]);
        $this->resetValidation();
    }
}
