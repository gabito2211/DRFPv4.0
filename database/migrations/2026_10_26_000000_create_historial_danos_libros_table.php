<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialDanosLibrosTable extends Migration
{
    public function up()
    {
        Schema::create('historial_danos_libros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('libro_id');
            $table->integer('prestamo_id')->unsigned()->nullable();
            $table->integer('sancion_id')->unsigned()->nullable();
            $table->integer('persona_id')->unsigned()->nullable();
            $table->string('tipo_evento')->default('dano'); // dano, reparacion, pago, otro
            $table->string('estado')->default('pendiente'); // pendiente, reparado, pagado, resuelto
            $table->text('descripcion')->nullable();
            $table->text('solucion')->nullable();
            $table->timestamp('fecha_evento')->nullable();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->bigInteger('sede_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('libro_id')->references('cota')->on('inventario')->onDelete('cascade');
            $table->foreign('prestamo_id')->references('id')->on('prestamo')->onDelete('set null');
            $table->foreign('sancion_id')->references('id')->on('sanciones')->onDelete('set null');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('set null');
            $table->index('libro_id');
            $table->index('sancion_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_danos_libros');
    }
}
