<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoraToVisitasGuiadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('visitas_guiadas') && !Schema::hasColumn('visitas_guiadas', 'hora')) {
            Schema::table('visitas_guiadas', function (Blueprint $table) {
                $table->time('hora')->nullable()->after('fecha_visita');
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
        if (Schema::hasTable('visitas_guiadas') && Schema::hasColumn('visitas_guiadas', 'hora')) {
            Schema::table('visitas_guiadas', function (Blueprint $table) {
                $table->dropColumn('hora');
            });
        }
    }
}
