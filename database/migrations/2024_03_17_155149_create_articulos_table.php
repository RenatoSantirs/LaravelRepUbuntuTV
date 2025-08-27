<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('categoria')->nullable();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('caracteristicas')->nullable();
            $table->string('imagen');
            $table->string('imagen2')->nullable();
            $table->string('imagen3')->nullable();
            $table->string('video')->nullable();
            $table->string('costo');
            $table->string('cantidad');
            $table->string('total');
            $table->string('sexo_de_prenda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
};
