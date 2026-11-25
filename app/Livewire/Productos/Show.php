<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public Producto $producto;

    public function mount(Producto $producto): void
    {
        $this->producto = $producto->load('ordenes');
    }

    public function render(): View
    {
        return view('livewire.productos.show')->layout('components.layout', [
            'title' => 'Producto: '.$this->producto->nombre,
        ]);
    }
}
