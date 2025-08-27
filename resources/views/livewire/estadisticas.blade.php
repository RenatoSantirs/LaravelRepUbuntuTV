<div>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="dark:bg-dark-eval-1 p-4 rounded-md shadow-md">
                <h2 class="text-lg font-semibold mb-">Ventas pasadas</h2>
                <ul>
                    @foreach ($ventas_pasadas as $fecha => $cantidad)
                        <li class="mb-2">{{ $fecha }}: {{ $cantidad }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="dark:bg-dark-eval-1 p-4 rounded-md shadow-md">
                <h2 class="text-lg font-semibold mb-2">Predicciones de ventas futuras</h2>
                <ul>
                    @foreach ($future_predictions as $prediction)
                        <li class="mb-2">{{ $prediction['fecha'] }}: {{ $prediction['cantidad'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Formulario para ingresar fechas de predicciones -->
        <form class="mt-8" wire:submit.prevent="predecirVentas">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <label for="fecha1" class="block mb-2">Fecha 1:</label>
                    <input type="date" id="fecha1" wire:model="fecha1"
                        class="dark:bg-dark-eval-1 border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400">
                </div>

                <div class="mb-4 md:mb-0">
                    <label for="fecha2" class="block mb-2">Fecha 2:</label>
                    <input type="date" id="fecha2" wire:model="fecha2"
                        class="dark:bg-dark-eval-1 border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400">
                </div>

                <div>
                    <label for="fecha3" class="block mb-2">Fecha 3:</label>
                    <input type="date" id="fecha3" wire:model="fecha3"
                        class="dark:bg-dark-eval-1 border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400">
                </div>
            </div>

            <button type="submit"
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out focus:outline-none">Predecir
                ventas</button>
        </form>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="flex justify-between">
                        <div class="card-body w-1/2 mr-4">
                            <h1>{{ $chart->options['chart_title'] }}</h1>
                            <div class="dark:bg-neutral-200">
                                {!! $chart->renderHtml() !!}
                            </div>
                        </div>
                        <div class="card-body w-1/2 ml-4">
                            <h1>{{ $chart3->options['chart_title'] }}</h1>
                            <div class="dark:bg-neutral-200">
                                {!! $chart3->renderHtml() !!}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="card-body w-1/2">
                            <h1>{{ $chart2->options['chart_title'] }}</h1>
                            <div class="dark:bg-neutral-200">
                                {!! $chart2->renderHtml() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        {!! $chart->renderChartJsLibrary() !!}
        {!! $chart->renderJs() !!}
        {!! $chart2->renderJs() !!}
        {!! $chart3->renderJs() !!}
    @endsection

</div>
