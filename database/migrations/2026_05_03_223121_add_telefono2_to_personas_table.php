<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTelefono2ToPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('personas', function (Blueprint $table) {
        // Añadimos el campo después del primer teléfono
        $table->string('telefono2')->nullable()->after('telefono');
    });
}

public function down()
{
    Schema::table('personas', function (Blueprint $table) {
        $table->dropColumn('telefono2');
    });
}
}
