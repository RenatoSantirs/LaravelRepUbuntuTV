<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticuloView extends Component
{
    public $id_usuario, $imagen, $nombre_ape, $direccion, $id_articulo, $nom_articulo, $costo, $cantidad,$total;
    public $mensajeError='';


    public function mount(Request $request)
    {
        $this->id_usuario = $request->input('id_usuario', '');
        $this->imagen = $request->input('imagen', '');
        $this->nombre_ape = $request->input('nombre_ape', '');
        $this->direccion = $request->input('direccion', '');
        $this->id_articulo = $request->input('id_articulo', '');
        $this->nom_articulo = $request->input('nom_articulo', '');
        $this->costo = $request->input('costo', 0);
        $this->cantidad = $request->input('cantidad', 1);
        $this->mensajeError = $request->input('mensajeError', '');
        $this->total = $this->costo * $this->cantidad;
    }
    public function updatedCantidad()
    {
        $this->total = floatval($this->costo) * intval($this->cantidad);
    }
    public function render()
    {
        return view('livewire.articulo-view')->layout('layouts.app');
    }
    protected $rules = [
        'id_usuario' => 'required|numeric',
        'imagen' => 'nullable|string',
        'nombre_ape' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'id_articulo' => 'required|numeric',
        'nom_articulo' => 'required|string|max:255',
        'costo' => 'required|numeric|min:0',
        'cantidad' => 'required|numeric|min:1',
        'total' => 'required|numeric|min:0',
    ];
    public function guardar()
    {
        $this->validate();

        // Obtener el artículo correspondiente al ID
        $articulo = Articulo::findOrFail($this->id_articulo);

        // Verificar si hay suficientes existencias
        if ($articulo->cantidad < $this->cantidad) {
            $this->mensajeError = '¡Error! ¡No hay suficientes existencias para realizar esta compra! Solo hay: ' . $articulo->cantidad . ' unidades.';
            return;
        }


        // Calcular la nueva cantidad de existencias después de la compra
        $nueva_cantidad = $articulo->cantidad - $this->cantidad;

        // Actualizar la cantidad de existencias del artículo en la base de datos
        $articulo->update(['cantidad' => $nueva_cantidad]);
        // Calcular el nuevo total del artículo
        $nuevo_total = $articulo->costo * $nueva_cantidad;

        // Actualizar el total del artículo en la base de datos
        $articulo->update(['total' => $nuevo_total]);

        // Crear la compra en la tabla de compras
        
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
        
        Storage::put('temp_compra.json', json_encode($data));

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
