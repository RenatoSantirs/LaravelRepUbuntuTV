<?php

namespace App\Http\Livewire;

use App\Models\Compra;
use DragonCode\Contracts\Cashier\Http\Request;
use Livewire\Component;
use Phpml\Classification\KNearestNeighbors;

class EstadisticasPrediccion extends Component
{
    public $fecha;

    public function render()
    {
        $chart_data = $this->obtenerChartData();

        return view('livewire.estadisticas-prediccion', [
            'chart_data' => $chart_data,
        ]);
    }

    public function obtenerPredicciones(Request $request)
    {
        $fecha = $request->input('fecha');
        $chart_data = $this->obtenerChartData($fecha);

        return response()->json($chart_data);
    }

    private function obtenerChartData($fecha = null)
    {
        $compras = $this->obtenerCompras($fecha);
        $classifier = $this->entrenarModelo($compras);
        $predicted = $this->realizarPrediccion($classifier);
        $top_5_predictions = $this->obtenerTop5Predicciones($predicted);

        $chart_data = [];
        foreach ($top_5_predictions as $articulo => $cantidad) {
            $chart_data[$articulo] = $cantidad;
        }

        return $chart_data;
    }

    private function obtenerCompras($fecha = null)
    {
        $query = Compra::query();
        if ($fecha) {
            $query->whereDate('fecha_compra', '<=', $fecha);
        }
        return $query->get();
    }

    private function entrenarModelo($compras)
    {
        $samples = [];
        $labels = [];

        foreach ($compras as $compra) {
            $samples[] = [(int)$compra->id_articulo];
            $labels[] = $compra->nom_articulo;
        }

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        return $classifier;
    }

    private function realizarPrediccion($classifier)
    {
        return $classifier->predict([[1], [2], [3], [4], [5]]);
    }

    private function obtenerTop5Predicciones($predicted)
    {
        $predictions = array_count_values($predicted);
        arsort($predictions);
        return array_slice($predictions, 0, 5, true);
    }
}
