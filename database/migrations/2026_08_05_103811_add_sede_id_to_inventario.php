<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeIdToInventario extends Migration
{
  protected $tablas = [

        'inventario'
    ];

    public function up()
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                // Se agrega la columna referenciando a la tabla sedes
                $table->bigInteger('sede_id')->unsigned()->nullable()->after('cota');
                $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropForeign(['sede_id']);
                $table->dropColumn('sede_id');
            });
        }
    }
}
