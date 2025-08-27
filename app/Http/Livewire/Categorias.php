<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Livewire\Component;

class Categorias extends Component
{

    public $categorias;

    public function mount()
    {
        // Obtener las categorías con su imagen
        $this->categorias = Articulo::select('categoria', 'imagen')->distinct()->get();
    }

    public function render()
    {
        return view('livewire.categorias', [
            'categorias' => $this->categorias
        ])->layout('layouts.app');
    }
}
