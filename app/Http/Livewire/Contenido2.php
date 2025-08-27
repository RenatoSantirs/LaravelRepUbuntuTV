<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use App\Models\Compra;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Contenido2 extends Component
{
    public
        $search = '',
        $searchiduser = '',
        $searchidNO = '';

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
    protected $articulos; // Cambiamos a propiedad protegida para paginar

    public function render()
    {
        $query = Compra::query();

        if ($this->searchiduser) {
            $query->where('id_usuario', 'like', '%' . $this->searchiduser . '%');
        }

        if ($this->searchidNO) {
            $query->where('id', 'like', '%' . $this->searchidNO . '%');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_ape', 'like', '%' . $this->search . '%')
                    ->orWhere('nom_articulo', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%')
                    ->orWhere('id_articulo', 'like', '%' . $this->search . '%');
            });
        }

        $this->articulos = $query->paginate(5);

        $user = auth()->user();
        // Verificar si el usuario tiene el rol de Administrador
        if ($user->hasRole('Administrator')) {
            return view('livewire.contenido2', ['articulos' => $this->articulos])->layout('layouts.app');
        } else {
            abort(404);
        }
    }


    // public function buscar()
    // {
    //     $this->articulos = Compra::where('nombre_ape', 'like', '%' . $this->search . '%')
    //         ->orWhere('nom_articulo', 'like', '%' . $this->search . '%')
    //         ->orWhere('direccion', 'like', '%' . $this->search . '%')
    //         ->orWhere('id_articulo', 'like', '%' . $this->search . '%')
    //         ->paginate(5);
    // }

    // public function buscariduser()
    // {
    //     $searchiduser = $this->searchiduser;
    //     $this->articulos = Compra::where('id_usuario', 'like', '%' . $this->searchiduser . '%')
    //         ->paginate(5);
    // }

    // public function buscaridNO()
    // {
    //     $searchidNO = $this->searchidNO;
    //     $this->articulos = Compra::where('id', 'like', '%' . $this->searchidNO . '%')
    //         ->paginate(5);
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
?>
