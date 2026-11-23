<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCedulaFieldToSedesTable extends Migration
{
    public function up()
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->string('cedula')->nullable()->after('direccion');
        });
    }

    public function down()
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn('cedula');
        });
    }
}