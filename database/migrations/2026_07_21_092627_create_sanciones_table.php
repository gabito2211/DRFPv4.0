<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSancionesTable extends Migration
{
    public function up()
    {
        Schema::create('sanciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prestamo_id')->unsigned();
            $table->integer('persona_id')->unsigned();
            $table->string('tipo_dano'); // Ej: "Retraso", "Libro deteriorado", "Pérdida", "Empastado roto"
            $table->enum('estado', ['pendiente', 'solventado'])->default('pendiente');
            $table->text('observacion_resolucion')->nullable(); // Ej: "Reparó el empastado", "Compró un ejemplar nuevo"
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();

            $table->foreign('prestamo_id')->references('id')->on('prestamo')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sanciones');
    }
}