<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Inicio extends Component
{

    use WithFileUploads;
    use WithPagination;

    public
        $search = '';

    protected $articulos;

    public $nombre_ape,
        $direccion,
        $nom_articulo,
        $cantidad,
        $total,
        $id_articulo,
        $costo,
        $imagen,
        $id_usuario,
        $stockDisponible;

    public $mensajeError = '';

    public $modal = false;
    public $costoMenorA10 = false,
        $costoMenorA50 = false,
        $costoMenorA100 = false,
        $costoMenorA300 = false,
        $busqhombre = false,
        $busqmujer = false;


    public function render()
    {
        $query = Articulo::query();
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->costoMenorA10) {
            $query->where('costo', '<=', 10);
        }

        if ($this->costoMenorA50) {
            $query->where('costo', '<=', 50);
        }

        if ($this->costoMenorA100) {
            $query->where('costo', '<=', 100);
        }
        if ($this->costoMenorA300) {
            $query->where('costo', '<=', 300);
        }
        if ($this->busqhombre) {
            $query->where('sexo_de_prenda', '=', 'Hombre');
        }
        if ($this->busqmujer) {
            $query->where('sexo_de_prenda', '=', 'Mujer');
        }

        $this->articulos = $query->paginate(9);
        return view('livewire.inicio', ['articulos' => $this->articulos]);
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'costoMenorA10' && $this->costoMenorA10) {
            $this->costoMenorA50 = false;
            $this->costoMenorA100 = false;
            $this->costoMenorA300 = false;
            $this->busqhombre = false;
            $this->busqmujer = false;
        } elseif ($propertyName == 'costoMenorA50' && $this->costoMenorA50) {
            $this->costoMenorA10 = false;
            $this->costoMenorA100 = false;
            $this->costoMenorA300 = false;
            $this->busqhombre = false;
            $this->busqmujer = false;
        } elseif ($propertyName == 'costoMenorA100' && $this->costoMenorA100) {
            $this->costoMenorA10 = false;
            $this->costoMenorA50 = false;
            $this->costoMenorA300 = false;
            $this->busqhombre = false;
            $this->busqmujer = false;
        } elseif ($propertyName == 'costoMenorA300' && $this->costoMenorA300) {
            $this->costoMenorA10 = false;
            $this->costoMenorA50 = false;
            $this->costoMenorA100 = false;
            $this->busqhombre = false;
            $this->busqmujer = false;
        } elseif ($propertyName == 'busqhombre' && $this->busqhombre) {
            $this->costoMenorA10 = false;
            $this->costoMenorA50 = false;
            $this->costoMenorA300 = false;
            $this->costoMenorA100 = false;
            $this->busqmujer = false;
        } elseif ($propertyName == 'busqmujer' && $this->busqmujer) {
            $this->costoMenorA10 = false;
            $this->costoMenorA50 = false;
            $this->costoMenorA300 = false;
            $this->costoMenorA100 = false;
            $this->busqhombre = false;
        }
    }
    // public function buscar()
    // {
    //     $search = $this->search;
    //     $this->articulos = Articulo::where('id', 'like', '%' . $this->search . '%')
    //         ->orWhere('nombre', 'like', '%' . $this->search . '%')
    //         ->paginate(5);
    // }

    protected $rules = [

        'nombre_ape' => 'required',
        'direccion' => 'required',
        'nom_articulo' => 'required',
        'cantidad' => 'required',
        'total' => 'required',
        'id_articulo' => 'required',
        'costo' => 'required',
        'imagen' => 'required'
    ];

    public function abrirModal()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->reset(['id_usuario', 'nombre_ape', 'direccion', 'cantidad', 'id_articulo', 'nom_articulo', 'costo', 'total', 'mensajeError']);
        $this->modal = false;
        $this->emit('closeModal');
    }

    public function comprar($id)
    {
        $id_usuario = Auth::id();
        $articulo = Articulo::findOrFail($id);

        $this->id_articulo = $id;
        $this->id_usuario = $id_usuario;
        $this->nombre_ape = $articulo->nombre_ape;
        $this->direccion = $articulo->direccion;
        $this->nom_articulo = $articulo->nombre;
        $this->cantidad = 1;
        $this->costo = $articulo->costo;
        $this->total = $this->costo * $this->cantidad;
        $this->imagen = $articulo->imagen;
        $this->stockDisponible = $articulo->cantidad; // Guardamos el stock del artículo

        $this->abrirModal();
    }

    public function updatedCantidad()
    {
        // Validamos que la cantidad esté dentro del rango permitido
        if ($this->cantidad < 1) {
            $this->cantidad = 1;
        } elseif ($this->cantidad > $this->stockDisponible) {
            $this->cantidad = $this->stockDisponible;
        }

        $this->total = floatval($this->costo) * intval($this->cantidad);
    }

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
