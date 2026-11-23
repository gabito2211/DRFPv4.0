<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstanteriaIdToInventario extends Migration
{
    public function up()
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->unsignedInteger('estanteria_id')->nullable()->after('sede_id');
            $table->foreign('estanteria_id')
                ->references('id')->on('estanterias')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropForeign(['estanteria_id']);
            $table->dropColumn('estanteria_id');
        });
    }
}
