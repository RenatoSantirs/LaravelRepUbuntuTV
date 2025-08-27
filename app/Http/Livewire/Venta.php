<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Venta extends Component
{
    public $id_usuario, $nombre_ape, $direccion, $nom_articulo, $cantidad, $total, $imagen, $id_articulo, $costo;

    public function mount()
    {
        $compra = session()->pull('compra', []);

        if (empty($compra)) {
            abort(404, 'Detalles de compra no encontrados.');
        }
     
        $this->id_usuario = $compra['id_usuario'];
        $this->nombre_ape = $compra['nombre_ape'];
        $this->direccion = $compra['direccion'];
        $this->nom_articulo = $compra['nom_articulo'];
        $this->cantidad = $compra['cantidad'];
        $this->total = $compra['total'];
        $this->imagen = $compra['imagen'];
        $this->id_articulo = $compra['id_articulo'];
        $this->costo = $compra['costo'];
    }

    public function render()
    {
        return view('livewire.venta')->layout('layouts.app');
    }
}
