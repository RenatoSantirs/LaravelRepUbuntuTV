<?php

namespace App\Http\Livewire;

use App\Models\Compra;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Livewire\Component;
use Phpml\Regression\LeastSquares;

class Estadisticas extends Component
{
    public $fecha1;
    public $fecha2;
    public $fecha3;
    public $future_predictions = [];

    public function render()
    {
        // Obtener los datos de ventas
        $ventas = Compra::select('created_at', 'cantidad')->get();

        // Crear los gráficos
        $chart_options = [
            'chart_title' => 'Top 5 Usuarios por Cantidad de Compras',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\Compra',
            'group_by_field' => 'id_usuario',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'cantidad',
            'chart_type' => 'bar',
            'sort_desc' => true,
            'take' => 5,
            'colors' => ['#FF5733', '#33FF57', '#5733FF', '#FFFF33', '#33FFFF'],
        ];
        $chart = new LaravelChart($chart_options);

        $chart_options_2 = [
            'chart_title' => 'Top 5 Artículos más vendidos',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\Compra',
            'group_by_field' => 'nom_articulo',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'cantidad',
            'chart_type' => 'pie',
            'colors' => ['orange', 'brown'],
        ];
        $chart2 = new LaravelChart($chart_options_2);

        $chart_options_3 = [
            'chart_title'        => 'Ventas',
            'report_type'        => 'group_by_date',
            'model'              => 'App\Models\Compra',
            'group_by_field'     => 'created_at',
            'group_by_period'    => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field'    => 'cantidad',
            'chart_type'         => 'line',
            'filter_field'       => 'created_at',
            'filter_days'        => '30',
            'group_by_field_format' => 'Y-m-d',
            'column_class'       => 'col-md-12',
            'entries_number'     => '5',
            'color'              => 'green',
        ];
        $chart3 = new LaravelChart($chart_options_3);

        $ventas = Compra::where('created_at', '>=', now()->subMonths(3))->get();

        // Agrupar las compras por fecha y sumar las cantidades de cada día
        $ventas_pasadas = $ventas->groupBy(function ($venta) {
            return $venta->created_at->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('cantidad');
        });

        // Convertir la colección en un array asociativo
        $ventas_pasadas = $ventas_pasadas->toArray();

        // Ordenar el array asociativo por clave (fecha)
        ksort($ventas_pasadas);

        return view('livewire.estadisticas', compact('chart', 'chart2', 'chart3', 'ventas', 'ventas_pasadas'))->layout('layouts.app');
    }

    public function predecirVentas()
    {
        // Limpiar las predicciones anteriores
        $this->future_predictions = [];
        // Obtener las fechas ingresadas en el formulario
        $fechas = [
            strtotime($this->fecha1),
            strtotime($this->fecha2),
            strtotime($this->fecha3)
        ];

        $ventas = Compra::select('created_at', 'cantidad')->get();
        $samples = [];
        $targets = [];

        foreach ($ventas as $venta) {
            $samples[] = [strtotime($venta->created_at)];
            $targets[] = $venta->cantidad;
        }

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        foreach ($fechas as $date) {
            $predicted_quantity = (int) $regression->predict([$date]);
            $this->future_predictions[] = [
                'fecha' => date('Y-m-d', $date),
                'cantidad' => $predicted_quantity,
            ];
        }
    }
    
}
