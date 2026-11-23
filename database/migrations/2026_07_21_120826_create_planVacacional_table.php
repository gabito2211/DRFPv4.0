<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanVacacionalTable extends Migration
{
    public function up()
    {
        Schema::create('planes_vacacionales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->date('desde');
            $table->date('hasta');
            $table->enum('estado', ['activo', 'inactivo', 'completado'])->default('inactivo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planes_vacacionales');
    }
}