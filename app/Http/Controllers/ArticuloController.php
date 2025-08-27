<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Support\Facades\Auth;


class ArticuloController extends Controller
{
    public $articulo;

    public $modal = false;

    public function render($categoria, $nombre, $id_articulo)
    {
        $articulo = Articulo::where('id', $id_articulo)
            ->where('categoria', $categoria)
            ->where('nombre', $nombre)
            ->firstOrFail();

        return view('articulos.' . $categoria . '.' . $id_articulo, ['articulo' => $articulo])->layout('layouts.app');
    }
}
