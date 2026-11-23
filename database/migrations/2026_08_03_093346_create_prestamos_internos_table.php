<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestamosInternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos_internos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cedula');
            $table->string('nombre_lector');
            $table->string('cota_libro');
            $table->string('titulo_libro');
            $table->date('fecha');
            $table->time('hora_prestamo');
            $table->time('hora_devolucion')->nullable();
            $table->enum('estado', ['activo', 'devuelto'])->default('activo');
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
        Schema::dropIfExists('prestamos_internos');
    }
}
