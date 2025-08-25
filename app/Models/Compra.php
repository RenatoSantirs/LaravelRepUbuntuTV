<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['id_usuario', 'nombre_ape', 'direccion', 'nom_articulo', 'id_articulo', 'cantidad', 'costo', 'total', 'imagen'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
?>