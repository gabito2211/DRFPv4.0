<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarioTable extends Migration
{
    public function up()
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->string('cota')->primary();
            $table->string('titulo'); 
            $table->string('autor');
            $table->integer('volumen')->nullable();
            $table->string('categoria');
            $table->string('ubicacion');
            $table->string('ingreso')->nullable();
            $table->enum('estado', ['bueno', 'deteriorado']); 
            $table->text('observaciones')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventario');
    }
}