@can('manage tasks')
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Buscar por ID Usuario:</label>
        <div class="mb-4">
            <input wire:model.debounce.500ms="searchiduser" type="text" id="searchiduser" placeholder="Buscar..."
                class="border border-gray-300 rounded-md px-3 py-2 w-full dark:bg-dark-eval-3 dark:text-white">
        </div>

        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Buscador(nom. comprador,nom.
            articulo,dirección):</label>
        <div class="mb-4">
            <input wire:model.debounce.500ms="search" type="text" id="search" placeholder="Buscar..."
                class="border border-gray-300 rounded-md px-3 py-2 w-full dark:bg-dark-eval-3 dark:text-white">
        </div>

        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Buscar por Numero de orden:</label>
        <div class="mb-4">
            <input wire:model.debounce.500ms="searchidNO" type="text" id="searchidNO" placeholder="Buscar..."
                class="border border-gray-300 rounded-md px-3 py-2 w-full dark:bg-dark-eval-3 dark:text-white">
        </div>
        @if ($articulos)
            <div>
                <x-slot name="header">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <h2 class="text-xl font-semibold leading-tight">
                            {{ __('Dashboard') }}
                        </h2>
                        <x-button target="_blank" href="https://github.com/kamona-wd/kui-laravel-jetstream" variant="black"
                            class="items-center max-w-xs gap-2">
                            <x-icons.github class="w-6 h-6" aria-hidden="true" />
                            <span>Star on Github</span>
                        </x-button>
                    </div>
                </x-slot>

                <div class="py-12 dark:bg-dark-eval-1">
                    <div class="max-w-7xl mx-auto sm:px6 lg:px-8 dark:bg-dark-eval-1">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-dark-eval-3">

                            @if (session()->has('message'))
                                <div class="bg-teal-100 rounded-b text-teal-900 px-4 py-4 shadow-md my-3" role="alert">
                                    <div class="flex">
                                        <div>
                                            <h4>{{ session('message') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($modal)
                                @include('comprarArt.comprar', ['imagen' => $imagen])
                            @endif
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full">
                                    <thead class="text-xs bg-gray-800 text-white uppercase">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">
                                                Acciones
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Numero de orden
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                ID de usuario
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Nombre del comprador
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                ID articulo
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Nombre del articulo
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Imagen
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Costo
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Cantidad
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Total
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Direccion
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Fecha de creación
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Fecha de edición
                                            </th>
                                            <th scope="col" class="py-3 px-6">
                                                Acciones
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($articulos as $articulo)
                                            <tr
                                                class="hover:bg-yellow-100 dark:hover:bg-neutral-200 dark:hover:text-slate-900">
                                                <td class="flex justify-center py-14 px-6">
                                                    <div class="flex flex-col items-center space-y-6">
                                                        <button wire:click="borrar({{ $articulo->id }})"
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4">Borrar</button>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->id }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->id_usuario }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->nombre_ape }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->id_articulo }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->nom_articulo }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    <img src="{{ asset('storage/' . $articulo->imagen) }}" width="200">
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->costo }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->cantidad }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->total }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->direccion }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->created_at }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $articulo->updated_at }}
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                        @if ($articulos->isEmpty())
                                            <tr>
                                                <td colspan="13">
                                                    <h1>No se encontraron resultados.</h1>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p>No se encontraron resultados.</p>
        @endif
        <div class="mt-4">
            {{ $articulos->links() }}
        </div>
    </div>
@endcan
