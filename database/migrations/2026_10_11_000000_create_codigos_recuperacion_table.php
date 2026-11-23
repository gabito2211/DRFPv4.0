<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigosRecuperacionTable extends Migration
{
    public function up()
    {
        Schema::create('codigos_recuperacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('codigo_hash');
            $table->string('generado_por')->nullable();
            $table->integer('intentos')->default(0);
            $table->timestamp('expira')->nullable();
            $table->boolean('usado')->default(false);
            $table->timestamp('usado_en')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('expira');
        });
    }

    public function down()
    {
        Schema::dropIfExists('codigos_recuperacion');
    }
}