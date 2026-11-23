<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestamoTable extends Migration
{
    public function up()
    {
        Schema::create('prestamo', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('lector_id');
            $table->string('libro_id');
            $table->date('fecha_salida');
            $table->date('fecha_devolucion');
            $table->text('condiciones_salida')->nullable();
            $table->text('condiciones_regreso')->nullable();
            $table->enum('estatus', ['pendiente', 'entregado'])->default('pendiente');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestamo');
    }
}