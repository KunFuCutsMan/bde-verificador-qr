<?php

namespace App\Livewire\Ordenes;

use App\Models\Orden;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public Orden $orden;

    public function mount(Orden $orden): void
    {
        $this->orden = $orden->load('productos');
    }

    public function render(): View
    {
        return view('livewire.ordenes.show')->layout('components.layout', [
            'title' => 'Orden #'.$this->orden->id,
        ]);
    }
}
