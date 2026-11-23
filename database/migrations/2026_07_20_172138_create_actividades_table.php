<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesTable extends Migration
{
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_vacacional_id');
            $table->string('actividad');
            $table->time('hora');
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->string('objetivo');
            $table->integer('cantidad');
            $table->unsignedInteger('sede_id');
            $table->string('responsable');
            $table->unsignedInteger('encargado_id')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('plan_vacacional_id');
            $table->index('sede_id');
            $table->index('fecha');
            
            // Llaves foráneas (se crearán después de que existan las tablas padres)
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}