
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('cam1') }}
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
                                            <img src="{{ asset('storage/public/imagenes/imagen_679059f2563c1.jpg') }}" class="w-96 h-96" alt="Imagen del producto">
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
                                <h2 class="text-2xl font-bold mb-4">cam1</h2>
                                <p class="text-lg mb-4">Marca: Gucci</p>
                                <p class="text-lg mb-4">Costo: {{ 0.01 }}</p>
                                <p class="text-lg mb-4">Cantidad disponible: {{ 50000 }}</p>
                                <p class="text-lg mb-4">Total: {{ 500 }}</p>
                                <p class="text-lg mb-4">Sexo: Hombre</p>
                                <button wire:click="comprar({{ 4 }})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4">Comprar</button>
                            </div>
                        </div>
                        <div class="mt-8">

                            <p class="text-lg mb-4">Descripción: </p>
                <p>d1d1</p>
                            <p class="text-lg mb-4">Características</p>
                <p>c1c1</p>	
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
