<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('personas', function (Blueprint $table) {
        $table->increments('id');
        $table->string('cedula')->unique(); // Importante: unique para que no se repitan
        $table->string('nombre');
        $table->char('sexo', 1); // 'M' o 'F'
        $table->string('telefono')->nullable();
        $table->string('correo')->nullable();
        $table->string('comunidad')->nullable();
        $table->text('direccion')->nullable();
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
        Schema::dropIfExists('personas');
    }
}
