<div>
    <div class="mb-4">
        <input wire:model.debounce.500ms="search" type="text" id="search" placeholder="Buscar..."
            class="border border-gray-300 rounded-md px-3 py-2 w-full dark:bg-dark-eval-3 dark:text-white">
    </div>
    <div style="display: flex;">
        <div class="mb-4" style="margin-right: 10px;">
            <input wire:model.debounce.500ms="costoMenorA10" type="checkbox" id="costo_menor_a_10">
            <label for="costo_menor_a_10">Costo menor a 10</label>
        </div>
        <div class="mb-4" style="margin-right: 10px;">
            <input wire:model.debounce.500ms="costoMenorA50" type="checkbox" id="costo_menor_a_50">
            <label for="costo_menor_a_50">Costo menor a 50</label>
        </div>
        <div class="mb-4" style="margin-right: 10px;">
            <input wire:model.debounce.500ms="costoMenorA100" type="checkbox" id="costo_menor_a_100">
            <label for="costo_menor_a_100">Costo menor a 100</label>
        </div>
        <div class="mb-4">
            <input wire:model.debounce.500ms="costoMenorA300" type="checkbox" id="costo_menor_a_300">
            <label for="costo_menor_a_300">Costo menor a 300</label>
        </div>
        <div class="mb-4">
            <input wire:model.debounce.500ms="busqhombre" type="checkbox" id="busqhombre">
            <label for="busqhombre">Hombre</label>
        </div>
        <div class="mb-4">
            <input wire:model.debounce.500ms="busqmujer" type="checkbox" id="busqmujer">
            <label for="busqmujer">Mujer</label>
        </div>
    </div>

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
                @if ($articulos)
                    <div class="grid grid-cols-3 gap-6">
                        @foreach ($articulos as $articulo)
                            <div
                                class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-400 dark:bg-dark-eval-2">
                                <div>
                                    <img src="{{ asset('storage/' . $articulo->imagen) }}"
                                        class="w-full h-48 object-cover" alt="Imagen del artículo">
                                </div>
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold">{{ $articulo->nombre }}</h2>
                                    <p>ID: {{ $articulo->id }}</p>
                                    <p>Costo: {{ $articulo->costo }}</p>
                                    <p>Cantidad: {{ $articulo->cantidad }}</p>
                                    <p>Total: {{ $articulo->total }}</p>
                                    <div class="mt-2">
                                        <button wire:click="comprar({{ $articulo->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                            @if ($articulo->categoria && $articulo->nombre && $articulo->id)
                                            <a href="{{ route('articulo.render', [
                                                'categoria' => $articulo->categoria ?? 'sin-categoria',
                                                'nombre' => $articulo->nombre,
                                                'id_articulo' => $articulo->id,
                                            ]) }}" class="text-blue-500 hover:underline">
                                                Ver Detalle
                                            </a>
                                        @else
                                            <span class="text-gray-500">Sin categoría</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Mensaje de no se encontraron resultados -->
                @if ($articulos->isEmpty())
                    <h1 class="text-center mt-6">No se encontraron resultados.</h1>
                @endif

            </div>
        </div>
    </div>
</div>
