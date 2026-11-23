<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiasPrestamoToInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('inventario') && !Schema::hasColumn('inventario', 'dias_prestamo')) {
            Schema::table('inventario', function (Blueprint $table) {
                $table->unsignedSmallInteger('dias_prestamo')->nullable()->after('volumen')
                    ->comment('Límite de días de préstamo. NULL = usa el plazo estándar');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('inventario') && Schema::hasColumn('inventario', 'dias_prestamo')) {
            Schema::table('inventario', function (Blueprint $table) {
                $table->dropColumn('dias_prestamo');
            });
        }
    }
}
