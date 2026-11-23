<?php
// database/migrations/2026_08_15_000001_add_encargado_id_to_sedes_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEncargadoIdToSedesTable extends Migration
{
    public function up()
    {
        Schema::table('sedes', function (Blueprint $table) {
            // Verificar si la columna no existe
            if (!Schema::hasColumn('sedes', 'encargado_id')) {
                // encargados.id es increments (int), usamos unsignedInteger
                $table->unsignedInteger('encargado_id')->nullable()->after('direccion');
                $table->foreign('encargado_id')
                      ->references('id')
                      ->on('encargados')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('sedes', function (Blueprint $table) {
            if (Schema::hasColumn('sedes', 'encargado_id')) {
                $table->dropForeign(['encargado_id']);
                $table->dropColumn('encargado_id');
            }
        });
    }
}