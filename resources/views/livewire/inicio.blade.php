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


                        {{-- <button wire:click="crear()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 my-3">Nuevo</button> --}}
                        @if ($modal)
                            @include('comprarArt.comprar', ['imagen' => $imagen])
                        @endif
                        <div class="grid grid-cols-3 gap-6">
                            @foreach ($articulos as $articulo)
                                <div
                                    class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-400 dark:bg-dark-eval-2">
                                    <img src="{{ asset('storage/' . $articulo->imagen) }}"
                                        class="w-full h-48 object-cover" alt="Imagen del artículo">
                                    <div class="p-4">
                                        <h2 class="text-lg font-semibold">{{ $articulo->nombre }}</h2>
                                        <p>ID: {{ $articulo->id }}</p>
                                        <p>Costo: {{ $articulo->costo }}</p>
                                        <p>Cantidad: {{ $articulo->cantidad }}</p>
                                        <p>Total: {{ $articulo->total }}</p>
                                        <div class="mt-2">
                                            <button wire:click="comprar({{ $articulo->id }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                                            {{-- <a href="{{ route('articulo.render', ['id_articulo' => $articulo->id]) }}"
                                                class="text-blue-500 hover:underline">Ver Detalle</a> --}}
                                            @if ($articulo->categoria && $articulo->nombre && $articulo->id)
                                                <a href="{{ route('articulo.render', [
                                                    'categoria' => $articulo->categoria ?? 'sin-categoria',
                                                    'nombre' => $articulo->nombre,
                                                    'id_articulo' => $articulo->id,
                                                ]) }}"
                                                    class="text-blue-500 hover:underline">
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

                        @if ($articulos->isEmpty())
                            <h1 class="text-center mt-6">No se encontraron resultados.</h1>
                        @endif

                    </div>
                </div>
            </div>
        @else
            <p>No se encontraron resultados.</p>
    @endif
    <div class="mt-4">
        {{ $articulos->links() }}
    </div>

    <div>
        <div id="adblock"
            class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
                <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                    Por favor, desactiva tu bloqueador de anuncios(Adblock)
                </h2>
                <p class="text-white dark:text-gray-400">
                    Para continuar con la compra, desactiva el bloqueador de anuncios y refresca la página.
                </p>
                <p class="text-white dark:text-gray-400">
                    Para evitar errores, use otro navegador que no sea Brave por su bloqueador de trackers y anuncios.
                </p>
                <p class="text-white dark:text-gray-400">
                    Si prefiere seguir usando el navegador Brave desactive el bloqueador de trackers y anuncios
                </p>
                <p class="text-white dark:text-gray-400">
                    Ya que podría obtener errores en la página al momento de comprar. Gracias.
                </p>
            </div>
        </div>

        <div id="adtrackers"
            class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
                <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                    Por favor, desactiva tu bloqueador de anuncios
                </h2>
                <p class="text-white dark:text-gray-400">
                    Para continuar con la compra, desactiva el bloqueador de trackers y refresca la página.
                </p>
                <p class="text-white dark:text-gray-400">
                    Para evitar errores, use otro navegador que no sea Brave por su bloqueador de trackers y anuncios.
                </p>
                <p class="text-white dark:text-gray-400">
                    Si prefiere seguir usando el navegador Brave desactive el bloqueador de trackers y anuncios
                </p>
                <p class="text-white dark:text-gray-400">
                    Ya que podría obtener errores en la página al momento de comprar. Gracias.
                </p>
            </div>
        </div>

        <div id="AdblokAdtacker"
            class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 dark:bg-dark-eval-1">
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-md text-center">
                <h2 class="text-lg font-semibold text-white dark:text-gray-200">
                    Por favor, desactiva tu bloqueador de trackers y bloqueador de anuncios(AdBlockers)
                </h2>
                <p class="text-white dark:text-gray-400">
                    Para continuar con la compra, desactiva el bloqueador de trackers y anuncios, luego refresca la
                    página.
                </p>
                <p class="text-white dark:text-gray-400">
                    Para evitar errores usa otro navegador que no sea Brave y que no bloqueen trackers ni anuncios por
                    favor.
                </p>
                <p class="text-white dark:text-gray-400">
                    Ya que podría obtener errores en la página al momento de comprar. Gracias.
                </p>
            </div>
        </div>

        <script>
            // Inicializamos variables de estado
            var adblockDetected = false;
            var trackersDetected = false;

            // Intentamos cargar un script que podría ser bloqueado por adblockers
            var script = document.createElement('script');
            script.src =
                'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'; // Cambia esta URL a una fuente que pueda ser bloqueada por adblockers
            script.onload = function() {
                // Si el script se carga correctamente, no hacemos nada para adblock
            };
            script.onerror = function() {
                // Si el script no se carga (probablemente por un adblocker), detectamos adblock
                adblockDetected = true;
                document.getElementById('adblock').classList.remove('hidden');
            };

            // Agregamos el script al documento
            document.head.appendChild(script);

            // Intentamos cargar un pixel de seguimiento de Twitter
            var img = new Image();
            img.src =
                'https://analytics.twitter.com/i/adsct?txn_id=YOUR_TXN_ID&cust_params=&t=tracking_pixel'; // Pixel de seguimiento de Twitter
            img.onload = function() {
                // Si no se detecta un bloqueador de trackers, no hacemos nada para trackers
            };
            img.onerror = function() {
                // Si el pixel no se carga (probablemente por un bloqueador de trackers), detectamos trackers
                trackersDetected = true;
                document.getElementById('adtrackers').classList.remove('hidden');
            };

            // Agregamos el pixel al documento
            document.head.appendChild(img);

            // Esperamos a que ambos scripts terminen para decidir qué mostrar
            setTimeout(function() {
                if (adblockDetected && trackersDetected) {
                    // Si se detectan ambos, mostramos el div de ambos bloqueadores
                    document.getElementById('AdblokAdtacker').classList.remove('hidden');
                }
            }, 2000); // Esperamos 2 segundos para asegurar que ambos recursos se hayan intentado cargar
        </script>


    </div>
</div>
