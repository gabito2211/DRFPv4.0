<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('visitas', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('persona_id')->unsigned(); // El enlace con la tabla personas
        $table->date('fecha');
        $table->time('hora_entrada');
        $table->string('departamento');
        $table->timestamps();

        // Definimos la llave foránea
        $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
