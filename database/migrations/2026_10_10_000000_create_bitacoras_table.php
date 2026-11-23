<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitacorasTable extends Migration
{
    public function up()
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('user_name')->nullable();
            $table->string('accion');
            $table->string('modulo');
            $table->text('descripcion');
            $table->text('datos_antes')->nullable();
            $table->text('datos_despues')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('modulo');
            $table->index('accion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bitacoras');
    }
}
