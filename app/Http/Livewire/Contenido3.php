<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\Compra;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Contenido3 extends Component
{
    public $search = '';

    protected $articulos;

    use WithFileUploads;
    use WithPagination;

    public $nombre_ape,
        $direccion,
        $nom_articulo,
        $cantidad,
        $total,
        $id_articulo,
        $costo,
        $imagen;

    public $modal = false;

    public function render()
    {
        $id_usuario = Auth::id(); // Obtener el ID del usuario autenticado

        $query = Compra::where('id_usuario', $id_usuario); // Filtrar por el ID del usuario

        if ($this->search) {
            $query->where(function ($q) {
                $q->orWhere('nom_articulo', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%');
            });
        }

        $this->articulos = $query->paginate(5);

        return view('livewire.contenido3', ['articulos' => $this->articulos])->layout('layouts.app');
    }


    // public function buscar()
    // {
    //     $search = $this->search;
    //     $id_usuario = Auth::id(); // Obtener el ID del usuario autenticado

    //     $this->articulos = Compra::where('id_usuario', $id_usuario) // Filtrar por el ID del usuario
    //         ->where(function ($query) use ($search) {
    //             $query->where('nombre_ape', 'like', '%' . $search . '%')
    //                 ->orWhere('nom_articulo', 'like', '%' . $search . '%');
    //         })
    //         ->get();
    // }


    public function abrirModal()
    {
        $this->modal = true;
    }
    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function borrar($id)
    {
        // Encontrar la compra que se va a borrar
        $compra = Compra::find($id);

        // Obtener el artículo correspondiente a la compra
        $articulo = Articulo::findOrFail($compra->id_articulo);

        // Incrementar la cantidad de existencias del artículo
        $nueva_cantidad = $articulo->cantidad + $compra->cantidad;

        $articulo->update(['cantidad' => $nueva_cantidad]);

        $nuevo_total = $articulo->costo * $nueva_cantidad;

        // Actualizar el total del artículo en la base de datos
        $articulo->update(['total' => $nuevo_total]);
        // Eliminar la compra
        $compra->delete();

        session()->flash('message', 'Compra eliminada correctamente y cantidad de artículo actualizada');
    }
}
