<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeIdToAllTables extends Migration
{
    /**
     * Tablas que recibirán el campo sede_id.
    */ 
    protected $tablas = [
        //'personas', 
        'visitas',
        'carnets',
        'prestamo',
        'prestamos_internos',
        'visitas_guiadas',
        //'actividades',
        'sanciones',
        'planes_vacacionales',
        'encargados'
    ];

    public function up()
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                // Se agrega la columna referenciando a la tabla sedes
                $table->bigInteger('sede_id')->unsigned()->nullable()->after('id');
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