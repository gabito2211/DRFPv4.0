<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('carnets', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('persona_id')->unsigned(); // Relación con la tabla personas
        $table->string('num_carnet')->unique();    // El número que genera el error
        $table->string('profesion');
        $table->date('fecha_emision');
        $table->date('fecha_vencimiento');
        $table->string('estado')->default('Activo');
        $table->date('sancionado_hasta')->nullable();
        $table->timestamps();

        // Definir la clave foránea
        $table->foreign('persona_id')->references('id')->on('personas');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carnets');
    }
}
