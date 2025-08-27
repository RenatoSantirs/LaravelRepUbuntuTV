<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfirmarCompra extends Component
{
    public function render()
    {
        return view('livewire.confirmar-compra')->layout('layouts.app');
    }

    
}
