<?php
// database/migrations/2026_07_03_040246_create_encargado_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncargadoTable extends Migration
{
    public function up()
    {
        Schema::create('encargados', function (Blueprint $table) {
            $table->increments('id'); // En Laravel 5.4 se usa increments()
            $table->string('cedula')->unique();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('telefono2')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('encargados');
    }
}