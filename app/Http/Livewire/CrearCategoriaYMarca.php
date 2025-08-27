<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Support\Facades\Storage;

class CrearCategoriaYMarca extends Component
{
    use WithFileUploads; // Importar para manejar archivos

    public $categorias, $marcas;
    public $nombreCategoria, $imagenCategoria;
    public $nombreMarca;
    public $modalCategoria = false, $modalMarca = false;
    public $mostrarGuardar = false;

    public function updatedImagenCategoria()
    {
        if ($this->imagenCategoria) {
            $this->mostrarGuardar = true; // Cambia el botón a "Guardar" al subir una imagen
        }
    }
    public function procesarSiguiente()
    {
        // Activar botón de "Guardar"
        $this->mostrarGuardar = true;
    }
    public function mount()
    {
        $this->categorias = Categoria::all();
        $this->marcas = Marca::all();
    }

    public function abrirModalCategoria()
    {
        $this->reset(['nombreCategoria', 'imagenCategoria', 'mostrarGuardar']); // Reinicia todo al abrir el modal
        $this->modalCategoria = true;
    }

    public function abrirModalMarca()
    {
        $this->reset('nombreMarca');
        $this->modalMarca = true;
    }

    public function cerrarModal()
    {
        $this->modalCategoria = false;
        $this->reset(['nombreCategoria', 'imagenCategoria', 'mostrarGuardar']);

        $this->modalMarca = false;
    }

    public function agregarCategoria()
    {
        $this->validate([
            'nombreCategoria' => 'required|unique:categorias,nombre|max:255',
            'imagenCategoria' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Generar un nombre único para la imagen
        $nombreArchivo = uniqid('categoria_') . '.' . $this->imagenCategoria->getClientOriginalExtension();

        // Guardar la imagen en storage/app/public/images/categorias/
        $this->imagenCategoria->storeAs('images/categorias', $nombreArchivo, 'public');

        // Guardar en la base de datos solo el nombre del archivo
        Categoria::create([
            'nombre' => $this->nombreCategoria,
            'imagen' => $nombreArchivo, // Guardamos solo el nombre del archivo
        ]);

        // Limpiar campos y actualizar lista
        $this->reset(['nombreCategoria', 'imagenCategoria']);
        $this->categorias = Categoria::all();
        $this->cerrarModal();
    }


    public function eliminarCategoria($id)
    {
        $categoria = Categoria::find($id);
        if ($categoria) {
            // Eliminar imagen si existe
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $categoria->delete();
        }
        $tempFiles = Storage::files('livewire-tmp');
        foreach ($tempFiles as $tempFile) {
            Storage::delete($tempFile);
        }
        $this->categorias = Categoria::all();
    }

    public function agregarMarca()
    {
        $this->validate([
            'nombreMarca' => 'required|unique:marcas,nombre|max:255',
        ]);

        // Guardar en la base de datos
        Marca::create([
            'nombre' => $this->nombreMarca,
        ]);

        // Limpiar campo y actualizar lista
        $this->reset('nombreMarca');
        $this->marcas = Marca::all();
        $this->cerrarModal();
    }
    public function render()
    {
        return view('livewire.crear-categoria-y-marca')->layout('layouts.app');
    }
}
