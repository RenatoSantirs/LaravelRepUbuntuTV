<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Articulo;

class ArticuloVistaPrev extends Component
{
    public function render()
    {
        return view('livewire.articulo-vista-prev')->layout('layouts.app');
    }

    public $id_usuario, $imagen, $nombre_ape, $direccion, $id_articulo, $nom_articulo, $costo, $cantidad, $total, $cantidad_disponible ;
    public $mensajeError = '';


    public function mount(Request $request)
    {
        $this->id_usuario = $request->input('id_usuario', '');
        $this->imagen = $request->input('imagen', '');
        $this->nombre_ape = $request->input('nombre_ape', '');
        $this->direccion = $request->input('direccion', '');
        $this->id_articulo = $request->input('id_articulo', '');
        $this->nom_articulo = $request->input('nom_articulo', '');
        $this->costo = $request->input('costo', 0);

        // Obtener la cantidad disponible del artículo desde la BD
        $articulo = Articulo::find($this->id_articulo);
        $this->cantidad_disponible = $articulo ? $articulo->cantidad : 0;

        // Asegurar que la cantidad inicial no supere la cantidad disponible
        $this->cantidad = min($request->input('cantidad', 1), $this->cantidad_disponible);

        $this->total = $this->costo * $this->cantidad;
    }
    public function updatedCantidad()
    {
        $this->validate([
            'cantidad' => 'required|numeric|min:1|max:' . $this->cantidad_disponible,
        ]);
        $this->total = floatval($this->costo) * intval($this->cantidad);
    }

    protected $rules = [
        'id_usuario' => 'required|numeric',
        'imagen' => 'nullable|string',
        'nombre_ape' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'id_articulo' => 'required|numeric',
        'nom_articulo' => 'required|string|max:255',
        'costo' => 'required|numeric|min:0',
        'cantidad' => 'required|numeric|min:1|max:100', // <- Cambiaremos esto dinámicamente
        'total' => 'required|numeric|min:0',
    ];
    public function guardar()
    {
        $this->validate();

        $data = [
            'id_usuario' => $this->id_usuario,
            'nombre_ape' => $this->nombre_ape,
            'direccion' => $this->direccion,
            'nom_articulo' => $this->nom_articulo,
            'cantidad' => $this->cantidad,
            'imagen' => $this->imagen,
            'costo' => $this->costo,
            'total' => $this->total,
            'id_articulo' => $this->id_articulo,
        ];

        Storage::put('temp_compra.json', json_encode($data, JSON_PRETTY_PRINT));

        session()->put('compra', [
            'id_usuario' => $this->id_usuario,
            'nombre_ape' => $this->nombre_ape,
            'direccion' => $this->direccion,
            'nom_articulo' => $this->nom_articulo,
            'cantidad' => $this->cantidad,
            'imagen' => $this->imagen,
            'costo' => $this->costo,
            'total' => $this->total,
            'id_articulo' => $this->id_articulo,
        ]);
        return redirect()->route('venta');
    }
}
