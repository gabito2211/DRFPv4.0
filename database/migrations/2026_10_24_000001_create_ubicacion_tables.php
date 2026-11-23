<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbicacionTables extends Migration
{
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('pasillos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero', 50);
            $table->unsignedInteger('sala_id');
            $table->timestamps();

            $table->unique(['sala_id', 'numero']);
            $table->foreign('sala_id')
                ->references('id')->on('salas')
                ->onDelete('cascade');
        });

        Schema::create('estanterias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero', 50);
            $table->string('genero', 100);
            $table->unsignedInteger('pasillo_id');
            $table->timestamps();

            $table->unique(['pasillo_id', 'numero']);
            $table->foreign('pasillo_id')
                ->references('id')->on('pasillos')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('estanterias');
        Schema::dropIfExists('pasillos');
        Schema::dropIfExists('salas');
    }
}
