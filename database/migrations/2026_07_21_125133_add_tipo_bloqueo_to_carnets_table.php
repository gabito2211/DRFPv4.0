<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoBloqueoToCarnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        Schema::table('carnets', function (Blueprint $table) {
            // Agregamos la columna tipo_bloqueo (puede ser NULL si el carnet está activo)
            $table->string('tipo_bloqueo')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('carnets', function (Blueprint $table) {
            $table->dropColumn('tipo_bloqueo');
        });
    }
}
