<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    private const PER_PAGE = 10;

    use WithPagination;

    public string $search = '';

    public bool $formVisible = false;

    public ?int $productoId = null;

    public string $nombre = '';

    public string $SKU = '';

    public string $Proveedor = '';

    public string $cantidad = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function startCreating(): void
    {
        $this->resetForm();
        $this->formVisible = true;
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function startEditing(int $productoId): void
    {
        $producto = Producto::findOrFail($productoId);

        $this->productoId = $producto->id;
        $this->nombre = $producto->nombre;
        $this->SKU = $producto->SKU;
        $this->Proveedor = $producto->Proveedor;
        $this->cantidad = (string) $producto->cantidad;
        $this->formVisible = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'SKU' => ['required', 'string', 'max:255'],
            'Proveedor' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);

        if ($this->productoId === null) {
            Producto::create($validated);
            session()->flash('status', 'Producto creado correctamente.');
        } else {
            Producto::findOrFail($this->productoId)->update($validated);
            session()->flash('status', 'Producto actualizado correctamente.');
        }

        $this->resetForm();
    }

    public function delete(int $productoId): void
    {
        Producto::findOrFail($productoId)->delete();

        $lastPage = max(1, (int) ceil(Producto::count() / self::PER_PAGE));

        if ($this->getPage() > $lastPage) {
            $this->setPage($lastPage);
        }

        session()->flash('status', 'Producto eliminado correctamente.');
    }

    public function render(): View
    {
        $productos = Producto::query()
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($query): void {
                    $search = '%'.$this->search.'%';

                    $query->where('nombre', 'like', $search)
                        ->orWhere('SKU', 'like', $search)
                        ->orWhere('Proveedor', 'like', $search);
                });
            })
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.productos.index', [
            'productos' => $productos,
        ])->layout('components.layout', [
            'title' => 'Productos',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'productoId',
            'nombre',
            'SKU',
            'Proveedor',
            'cantidad',
            'formVisible',
        ]);
        $this->resetValidation();
    }
}
