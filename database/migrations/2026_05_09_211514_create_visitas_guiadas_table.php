<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitasGuiadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas_guiadas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('institucion'); // Ej. Escuela Bolivariana 'Nombre'
            $table->string('responsable'); // Nombre del docente o guía
            $table->string('encargado'); //nombre del encargado
            $table->integer('cantidad')->unsigned(); // Cantidad de personas
            $table->date('fecha_visita'); // Fecha del recorrido
            $table->time('hora')->nullable(); // Hora del recorrido
            $table->enum('tipo_recorrido', [
                'general', 
                'investigacion', 
                'infantil', 
                'tecnico'
            ]); // Basado en tus opciones del <select>
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
        Schema::dropIfExists('visitas_guiadas');
    }
}
