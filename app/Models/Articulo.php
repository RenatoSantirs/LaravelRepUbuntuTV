<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    protected $fillable = ['categoria','nombre','marca','descripcion','caracteristicas', 'imagen', 'imagen2', 'imagen3','video','costo','cantidad','total','sexo_de_prenda'];
}
