<?php

namespace App\Http\Livewire;

use App\Models\Articulo;
use Livewire\Component;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class Contenido1 extends Component
{
    use WithFileUploads;
    use WithPagination;

    public
        $nombre,
        $marca,
        $categoria,
        $descripcion,
        $caracteristicas,
        $imagen,
        $imagen2,
        $imagen3,
        $video,
        $costo,
        $cantidad,
        $total,
        $id_articulo,
        $search = '',
        $searchid = '',
        $user,
        $sexo_de_prenda,
        $id_usuario;

    protected $articulos;

    public $modal = false;
    protected $rules = [

        'nombre' => 'required',
        'costo' => 'required',
        'cantidad' => 'required',
        'sexo_de_prenda' => 'required',
        'imagen' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        'video' => 'nullable|mimes:mp4|max:10240',
    ];

    protected $messages = [

        'nombre.required' => 'Debe completar el campo nombre',
        'costo.required' => 'Debe completar el campo costo',
        'cantidad.required' => 'Debe completar el campo cantidad',
        'sexo_de_prenda.required' => 'Debe completar el campo sexo de la prenda',
        'imagen.image' => 'El archivo debe ser una imagen.',
        'imagen.mimes' => 'La imagen debe ser en formato PNG, JPG o JPEG.',
        'imagen.max' => 'La imagen no puede superar los 2MB.',
        'video.mimes' => 'El video debe estar en formato MP4.',
        'video.max' => 'El video no puede superar los 10MB.',
    ];

    public function render()
    {
        $query = Articulo::query();

        if ($this->searchid) {
            $query->where('id', 'like', '%' . $this->searchid . '%');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        $this->articulos = $query->paginate(5);

        $user = auth()->user();
        // Verificar si el usuario tiene el rol de Administrador
        if ($user->hasRole('Administrator')) {
            // Obtener categorías y marcas desde la base de datos
            $categorias = Categoria::all();
            $marcas = Marca::all();

            return view('livewire.contenido1', [
                'articulos' => $this->articulos,
                'categorias' => $categorias,
                'marcas' => $marcas
            ])->layout('layouts.app');
        } else {
            abort(404);
        }
    }

    // public function buscar()
    // {
    //     $search = $this->search;
    //     $this->articulos = Articulo::where('id', 'like', '%' . $this->search . '%')
    //         ->orWhere('nombre', 'like', '%' . $this->search . '%')
    //         ->get();
    // }


    public function crear()
    {
        $this->limpiarCampos();
        $this->abrirModal();
    }
    public function abrirModal()
    {
        $this->modal = true;
    }
    public function cerrarModal()
    {
        $this->modal = false;
    }
    public function limpiarCampos()
    {
        $this->categoria = '';
        $this->nombre = '';
        $this->marca = '';
        $this->descripcion = '';
        $this->caracteristicas = '';
        $this->imagen = '';
        $this->imagen2 = '';
        $this->imagen3 = '';
        $this->video = '';
        $this->costo = '';
        $this->cantidad = '';
        $this->sexo_de_prenda = '';
    }
    public function editar($id)
    {
        $articulo = articulo::findOrFail($id);
        $this->id_articulo = $id;
        $this->categoria = $articulo->categoria;
        $this->nombre = $articulo->nombre;
        $this->marca = $articulo->marca;
        $this->descripcion = $articulo->descripcion;
        $this->caracteristicas = $articulo->caracteristicas;
        $this->imagen = $articulo->imagen;
        $this->imagen2 = $articulo->imagen2;
        $this->imagen3 = $articulo->imagen3;
        $this->video = $articulo->video;
        $this->costo = $articulo->costo;
        $this->cantidad = $articulo->cantidad;
        $this->total = $articulo->costo * $articulo->cantidad;
        $this->sexo_de_prenda = $articulo->sexo_de_prenda;

        $this->abrirModal();
    }

    public function borrar($id)
    {
        $articulo = Articulo::find($id);

        if (!$articulo) {
            session()->flash('error', 'El artículo no existe.');
            return;
        }

        // Eliminar imágenes
        if ($articulo->imagen) {
            Storage::delete('imagenes/' . basename($articulo->imagen));
        }
        if ($articulo->imagen2) {
            Storage::delete('imagenes/' . basename($articulo->imagen2));
        }
        if ($articulo->imagen3) {
            Storage::delete('imagenes/' . basename($articulo->imagen3));
        }

        // Eliminar video
        if ($articulo->video) {
            Storage::delete('videos/' . basename($articulo->video));
        }

        // Eliminar archivos temporales de Livewire
        $tempFiles = Storage::files('livewire-tmp');
        foreach ($tempFiles as $tempFile) {
            Storage::delete($tempFile);
        }

        // Eliminar la vista si existe
        $categoria = $articulo->categoria;
        $viewPath = resource_path("views/articulos/{$categoria}/{$id}.blade.php");

        if (file_exists($viewPath)) {
            unlink($viewPath);
        }

        // Eliminar el artículo de la base de datos
        $articulo->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
    }


    public function guardar()
    {

        $this->validate();
        $id_usuario = Auth::id();

        $articulo = $this->id_articulo ? Articulo::findOrFail($this->id_articulo) : null;

        // Imagen 1
        $imagenAnterior = $articulo ? $articulo->imagen : null;
        if ($this->imagen instanceof \Livewire\TemporaryUploadedFile) {
            $nombreArchivo = uniqid('imagen_') . '.' . $this->imagen->getClientOriginalExtension();
            $imagen = $this->imagen->storeAs('imagenes', $nombreArchivo); // 🔹 Eliminado "public/"
        } else {
            $imagen = $imagenAnterior;
        }

        if ($imagenAnterior && $imagenAnterior !== $imagen) {
            Storage::delete('imagenes/' . basename($imagenAnterior)); // 🔹 Corregida la eliminación
        }

        // Imagen 2
        $imagenAnterior2 = $articulo ? $articulo->imagen2 : null;
        if ($this->imagen2 instanceof \Livewire\TemporaryUploadedFile) {
            $nombreArchivo2 = uniqid('imagen_') . '.' . $this->imagen2->getClientOriginalExtension();
            $imagen2 = $this->imagen2->storeAs('imagenes', $nombreArchivo2);
        } else {
            $imagen2 = $imagenAnterior2;
        }

        if ($imagenAnterior2 && $imagenAnterior2 !== $imagen2) {
            Storage::delete('imagenes/' . basename($imagenAnterior2));
        }

        // Imagen 3
        $imagenAnterior3 = $articulo ? $articulo->imagen3 : null;
        if ($this->imagen3 instanceof \Livewire\TemporaryUploadedFile) {
            $nombreArchivo3 = uniqid('imagen_') . '.' . $this->imagen3->getClientOriginalExtension();
            $imagen3 = $this->imagen3->storeAs('imagenes', $nombreArchivo3);
        } else {
            $imagen3 = $imagenAnterior3;
        }

        if ($imagenAnterior3 && $imagenAnterior3 !== $imagen3) {
            Storage::delete('imagenes/' . basename($imagenAnterior3));
        }

        // Video
        $videoAnterior = $articulo ? $articulo->video : null;
        if ($this->video instanceof \Livewire\TemporaryUploadedFile) {
            $nombreArchivo = uniqid('video_') . '.' . $this->video->getClientOriginalExtension();
            $video = $this->video->storeAs('videos', $nombreArchivo); // 🔹 Eliminado "public/"
        } else {
            $video = $videoAnterior;
        }

        if ($videoAnterior && $videoAnterior !== $video) {
            Storage::delete('videos/' . basename($videoAnterior));
        }

        // Verifica si el usuario autenticado tiene un ID igual a 1
        if (Auth::check() && Auth::user()->id == 1) {
            // Actualiza o crea un nuevo artículo
            $articulo = articulo::updateOrCreate(
                ['id' => $this->id_articulo],
                [
                    'categoria' => $this->categoria,
                    'nombre' => $this->nombre,
                    'marca' => $this->marca,
                    'descripcion' => $this->descripcion,
                    'caracteristicas' => $this->caracteristicas,
                    'imagen' => $imagen,
                    'imagen2' => $imagen2,
                    'imagen3' => $imagen3,
                    'video' => $video,
                    'costo' => $this->costo,
                    'cantidad' => $this->cantidad,
                    'total' => $this->costo * $this->cantidad,
                    'sexo_de_prenda' => $this->sexo_de_prenda,
                ]
            );

            // Obtiene el ID del artículo
            $id_articulo = $articulo->id;
            if ($video !== null) {
                if ($imagen2 === null && $imagen3 === null) {
                    $viewContent = <<<HTML

                    <x-app-layout>
                        <x-slot name="header">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold leading-tight">
                                {{ __('$this->nombre') }}
                            </h2>     
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

                        </div>
                        </x-slot>

                        <div class="py-12 dark:bg-dark-eval-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <div class="max-w-4xl mx-auto py-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                            <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                    <div class="slick-carousel">
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div class="w-96 h-96">
                                                            <video controls class="w-96 h-96">
                                                                <source src="{{ asset('storage/$articulo->video') }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Botón slick-prev -->
                                                <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                    <button class="slick-prev">Prev</button>
                                                </div>
                                                <!-- Botón slick-next -->
                                                <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                    <button class="slick-next">Next</button>
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                <p class="text-lg mb-4">Marca: $this->marca</p>
                                                <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                
                                                <form method="GET" action="{{ route('articulovistaprev') }}">
                                                    <input type="hidden" name="id_usuario" value="{{ $id_usuario }} ">
                                                    <input type="hidden" name="imagen" value="$articulo->imagen">
                                                    <input type="hidden" name="nombre_ape" value="">
                                                    <input type="hidden" name="direccion" value="">                                                    
                                                    <input type="hidden" name="id_articulo" value="{{ $id_articulo }}">
                                                    <input type="hidden" name="nom_articulo" value="$this->nombre">
                                                    <input type="hidden" name="costo" value="{{ $articulo->costo }}">
                                                    <input type="hidden" name="cantidad" value="1">

                                                    <button type="submit"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">
                                                        Comprar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-8">

                                            <p class="text-lg mb-4">Descripción: </p>
                                <p>$this->descripcion</p>
                                            <p class="text-lg mb-4">Características</p>
                                <p>$this->caracteristicas</p>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

                        <script>
                        $(document).ready(function() {
                            $('.slick-carousel').slick({
                                dots: false,
                                arrows: true,
                                prevArrow: $('.slick-prev'),
                                nextArrow: $('.slick-next'),
                                autoplay: true, // Habilita el autoplay
                                autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                            });
                        });
                        </script>
                    </x-app-layout>

                    HTML;
                } elseif ($imagen2 !== null && $imagen3 === null) {
                    $viewContent = <<<HTML

                    <x-app-layout>
                        <x-slot name="header">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold leading-tight">
                                {{ __('$this->nombre') }}
                            </h2>      
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

                        </div>
                        </x-slot>

                        <div class="py-12 dark:bg-dark-eval-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <div class="max-w-4xl mx-auto py-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                            <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                    <div class="slick-carousel">
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen2') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div class="w-96 h-96">
                                                            <video controls class="w-96 h-96">
                                                                <source src="{{ asset('storage/$articulo->video') }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Botón slick-prev -->
                                                <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                    <button class="slick-prev">Prev</button>
                                                </div>
                                                <!-- Botón slick-next -->
                                                <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                    <button class="slick-next">Next</button>
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                <p class="text-lg mb-4">Marca: $this->marca</p>
                                                <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                <button wire:click="comprar({{ $articulo->id }})"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                            </div>
                                        </div>
                                        <div class="mt-8">

                                            <p class="text-lg mb-4">Descripción: </p>
                                <p>$this->descripcion</p>
                                            <p class="text-lg mb-4">Características</p>
                                <p>$this->caracteristicas</p>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

                        <script>
                        $(document).ready(function() {
                            $('.slick-carousel').slick({
                                dots: false,
                                arrows: true,
                                prevArrow: $('.slick-prev'),
                                nextArrow: $('.slick-next'),
                                autoplay: true, // Habilita el autoplay
                                autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                            });
                        });
                        </script>
                    </x-app-layout>

                    HTML;
                } elseif ($imagen2 !== null && $imagen3 !== null) {
                    $viewContent = <<<HTML

                    <x-app-layout>
                        <x-slot name="header">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold leading-tight">
                                {{ __('$this->nombre') }}
                            </h2>
                            </x-button>
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

                        </div>
                        </x-slot>

                        <div class="py-12 dark:bg-dark-eval-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <div class="max-w-4xl mx-auto py-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                            <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                    <div class="slick-carousel">
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen2') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen3') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div class="w-96 h-96">
                                                            <video controls class="w-96 h-96">
                                                                <source src="{{ asset('storage/$articulo->video') }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Botón slick-prev -->
                                                <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                    <button class="slick-prev">Prev</button>
                                                </div>
                                                <!-- Botón slick-next -->
                                                <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                    <button class="slick-next">Next</button>
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                <p class="text-lg mb-4">Marca: $this->marca</p>
                                                <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                <button wire:click="comprar({{ $articulo->id }})"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                            </div>
                                        </div>
                                        <div class="mt-8">

                                            <p class="text-lg mb-4">Descripción: </p>
                                <p>$this->descripcion</p>
                                            <p class="text-lg mb-4">Características</p>
                                <p>$this->caracteristicas</p>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

                        <script>
                        $(document).ready(function() {
                            $('.slick-carousel').slick({
                                dots: false,
                                arrows: true,
                                prevArrow: $('.slick-prev'),
                                nextArrow: $('.slick-next'),
                                autoplay: true, // Habilita el autoplay
                                autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                            });
                        });
                        </script>
                    </x-app-layout>

                    HTML;
                } else {
                    $viewContent = <<<HTML

                    <x-app-layout>
                        <x-slot name="header">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold leading-tight">
                                {{ __('$this->nombre') }}
                            </h2>
                            </x-button>
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                            <link rel="stylesheet" type="text/css"
                                href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

                        </div>
                        </x-slot>

                        <div class="py-12 dark:bg-dark-eval-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <div class="max-w-4xl mx-auto py-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                            <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                    <div class="slick-carousel">
                                                        <div>
                                                            <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                        </div>
                                                        <div class="w-96 h-96">
                                                            <video controls class="w-96 h-96">
                                                                <source src="{{ asset('storage/$articulo->video') }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Botón slick-prev -->
                                                <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                    <button class="slick-prev">Prev</button>
                                                </div>
                                                <!-- Botón slick-next -->
                                                <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                    <button class="slick-next">Next</button>
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                <p class="text-lg mb-4">Marca: $this->marca</p>
                                                <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                <button wire:click="comprar({{ $articulo->id }})"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                            </div>
                                        </div>
                                        <div class="mt-8">

                                            <p class="text-lg mb-4">Descripción: </p>
                                <p>$this->descripcion</p>
                                            <p class="text-lg mb-4">Características</p>
                                <p>$this->caracteristicas</p>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

                        <script>
                        $(document).ready(function() {
                            $('.slick-carousel').slick({
                                dots: false,
                                arrows: true,
                                prevArrow: $('.slick-prev'),
                                nextArrow: $('.slick-next'),
                                autoplay: true, // Habilita el autoplay
                                autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                            });
                        });
                        </script>
                    </x-app-layout>

                    HTML;
                }
            } else {
                if ($imagen2 === null && $imagen3 === null) {
                    $viewContent = <<<HTML
    
                    <x-app-layout>
                        <x-slot name="header">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <h2 class="text-xl font-semibold leading-tight">
                                    {{ __('$this->nombre') }}
                                </h2>
                                <x-button target="_blank" href="https://github.com/kamona-wd/kui-laravel-jetstream" variant="black"
                                    class="items-center max-w-xs gap-2">
                                    <x-icons.github class="w-6 h-6" aria-hidden="true" />
                                    <span>Star on Github</span>
                                </x-button>
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
                    
                            </div>
                        </x-slot>
                    
                        <div class="py-12 dark:bg-dark-eval-1">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                        <div class="max-w-4xl mx-auto py-2">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                                <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                    <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                        <div class="slick-carousel">
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>                                            
                                                        </div>
                                                    </div>
                                                    <!-- Botón slick-prev -->
                                                    <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                        <button class="slick-prev">Prev</button>
                                                    </div>
                                                    <!-- Botón slick-next -->
                                                    <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                        <button class="slick-next">Next</button>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col justify-center">
                                                    <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                    <p class="text-lg mb-4">Marca: $this->marca</p>
                                                    <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                    <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                    <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                    <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                    <button wire:click="comprar({{ $articulo->id }})"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                                </div>
                                            </div>
                                            <div class="mt-8">
                    
                                                <p class="text-lg mb-4">Descripción: </p>
                                    <p>$this->descripcion</p>
                                                <p class="text-lg mb-4">Características</p>
                                    <p>$this->caracteristicas</p>	
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
                    
                        <script>
                            $(document).ready(function() {
                                $('.slick-carousel').slick({
                                    dots: false,
                                    arrows: true,
                                    prevArrow: $('.slick-prev'),
                                    nextArrow: $('.slick-next'),
                                    autoplay: true, // Habilita el autoplay
                                    autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                                });
                            });
                        </script>
                    </x-app-layout>
                    
                    HTML;
                } elseif ($imagen2 !== null && $imagen3 === null) {
                    $viewContent = <<<HTML
                    
                    <x-app-layout>
                        <x-slot name="header">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <h2 class="text-xl font-semibold leading-tight">
                                    {{ __('$this->nombre') }}
                                </h2>
                                <x-button target="_blank" href="https://github.com/kamona-wd/kui-laravel-jetstream" variant="black"
                                    class="items-center max-w-xs gap-2">
                                    <x-icons.github class="w-6 h-6" aria-hidden="true" />
                                    <span>Star on Github</span>
                                </x-button>
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
                    
                            </div>
                        </x-slot>
                    
                        <div class="py-12 dark:bg-dark-eval-1">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                        <div class="max-w-4xl mx-auto py-2">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                                <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                    <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                        <div class="slick-carousel">
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen2') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>                                            
                                                        </div>
                                                    </div>
                                                    <!-- Botón slick-prev -->
                                                    <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                        <button class="slick-prev">Prev</button>
                                                    </div>
                                                    <!-- Botón slick-next -->
                                                    <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                        <button class="slick-next">Next</button>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col justify-center">
                                                    <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                    <p class="text-lg mb-4">Marca: $this->marca</p>
                                                    <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                    <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                    <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                    <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                    <button wire:click="comprar({{ $articulo->id }})"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                                </div>
                                            </div>
                                            <div class="mt-8">
                    
                                                <p class="text-lg mb-4">Descripción: </p>
                                    <p>$this->descripcion</p>
                                                <p class="text-lg mb-4">Características</p>
                                    <p>$this->caracteristicas</p>	
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
                    
                        <script>
                            $(document).ready(function() {
                                $('.slick-carousel').slick({
                                    dots: false,
                                    arrows: true,
                                    prevArrow: $('.slick-prev'),
                                    nextArrow: $('.slick-next'),
                                    autoplay: true, // Habilita el autoplay
                                    autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                                });
                            });
                        </script>
                    </x-app-layout>
                    
                    HTML;
                } elseif ($imagen2 !== null && $imagen3 !== null) {
                    $viewContent = <<<HTML
                    
                    <x-app-layout>
                        <x-slot name="header">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <h2 class="text-xl font-semibold leading-tight">
                                    {{ __('$this->nombre') }}
                                </h2>
                                <x-button target="_blank" href="https://github.com/kamona-wd/kui-laravel-jetstream" variant="black"
                                    class="items-center max-w-xs gap-2">
                                    <x-icons.github class="w-6 h-6" aria-hidden="true" />
                                    <span>Star on Github</span>
                                </x-button>
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
                    
                            </div>
                        </x-slot>
                    
                        <div class="py-12 dark:bg-dark-eval-1">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                        <div class="max-w-4xl mx-auto py-2">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                                <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                    <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                        <div class="slick-carousel">
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen2') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen3') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>                                            
                                                        </div>
                                                    </div>
                                                    <!-- Botón slick-prev -->
                                                    <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                        <button class="slick-prev">Prev</button>
                                                    </div>
                                                    <!-- Botón slick-next -->
                                                    <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                        <button class="slick-next">Next</button>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col justify-center">
                                                    <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                    <p class="text-lg mb-4">Marca: $this->marca</p>
                                                    <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                    <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                    <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                    <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                    <button wire:click="comprar({{ $articulo->id }})"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                                </div>
                                            </div>
                                            <div class="mt-8">
                    
                                                <p class="text-lg mb-4">Descripción: </p>
                                    <p>$this->descripcion</p>
                                                <p class="text-lg mb-4">Características</p>
                                    <p>$this->caracteristicas</p>	
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
                    
                        <script>
                            $(document).ready(function() {
                                $('.slick-carousel').slick({
                                    dots: false,
                                    arrows: true,
                                    prevArrow: $('.slick-prev'),
                                    nextArrow: $('.slick-next'),
                                    autoplay: true, // Habilita el autoplay
                                    autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                                });
                            });
                        </script>
                    </x-app-layout>
                    
                    HTML;
                } else {
                    $viewContent = <<<HTML
                    
                    <x-app-layout>
                        <x-slot name="header">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <h2 class="text-xl font-semibold leading-tight">
                                    {{ __('$this->nombre') }}
                                </h2>
                                <x-button target="_blank" href="https://github.com/kamona-wd/kui-laravel-jetstream" variant="black"
                                    class="items-center max-w-xs gap-2">
                                    <x-icons.github class="w-6 h-6" aria-hidden="true" />
                                    <span>Star on Github</span>
                                </x-button>
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
                                <link rel="stylesheet" type="text/css"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
                    
                            </div>
                        </x-slot>
                    
                        <div class="py-12 dark:bg-dark-eval-1">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-dark-eval-1">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg  dark:bg-dark-eval-3">
                                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                        <div class="max-w-4xl mx-auto py-2">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                                <div class="flex flex-col justify-center relative dark:bg-dark-eval-0"> <!-- Agregado relative al contenedor del carrusel -->
                                                    <div class="max-w-xs mx-auto py-9"> <!-- Mover el max-w-4xl al contenedor del carrusel -->
                                                        <div class="slick-carousel">
                                                            <div>
                                                                <img src="{{ asset('storage/$articulo->imagen') }}" class="w-96 h-96" alt="Imagen del producto">
                                                            </div>                                            
                                                        </div>
                                                    </div>
                                                    <!-- Botón slick-prev -->
                                                    <div class="absolute inset-y-0 left-0 flex items-center justify-center">
                                                        <button class="slick-prev">Prev</button>
                                                    </div>
                                                    <!-- Botón slick-next -->
                                                    <div class="absolute inset-y-0 right-0 flex items-center justify-center">
                                                        <button class="slick-next">Next</button>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col justify-center">
                                                    <h2 class="text-2xl font-bold mb-4">$this->nombre</h2>
                                                    <p class="text-lg mb-4">Marca: $this->marca</p>
                                                    <p class="text-lg mb-4">Costo: {{ $articulo->costo }}</p>
                                                    <p class="text-lg mb-4">Cantidad disponible: {{ $articulo->cantidad }}</p>
                                                    <p class="text-lg mb-4">Total: {{ $articulo->total }}</p>
                                                    <p class="text-lg mb-4">Sexo: $this->sexo_de_prenda</p>
                                                    <button wire:click="comprar({{ $articulo->id }})"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                                </div>
                                            </div>
                                            <div class="mt-8">
                    
                                                <p class="text-lg mb-4">Descripción: </p>
                                    <p>$this->descripcion</p>
                                                <p class="text-lg mb-4">Características</p>
                                    <p>$this->caracteristicas</p>	
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
                    
                        <script>
                            $(document).ready(function() {
                                $('.slick-carousel').slick({
                                    dots: false,
                                    arrows: true,
                                    prevArrow: $('.slick-prev'),
                                    nextArrow: $('.slick-next'),
                                    autoplay: true, // Habilita el autoplay
                                    autoplaySpeed: 3000 // Establece el intervalo de cambio de imagen a 3 segundos
                                });
                            });
                        </script>
                    </x-app-layout>
                    
                    HTML;
                }
            }

            $categoria = $this->categoria;

            // Genera una ruta para la vista en el directorio de vistas
            $viewPath = resource_path('views/articulos/' . $categoria . '/' . $id_articulo . '.blade.php');

            // Asegúrate de que el directorio de vistas exista, si no, créalo
            File::ensureDirectoryExists(dirname($viewPath));

            // Almacena el contenido de la vista en la ubicación especificada
            file_put_contents($viewPath, $viewContent);
        }

        // Mensaje de éxito
        session()->flash('message', $this->id_articulo ? '¡Actualización exitosa!' : '¡Guardado exitoso!');
        // Cierra el modal
        $this->cerrarModal();
    }
}
