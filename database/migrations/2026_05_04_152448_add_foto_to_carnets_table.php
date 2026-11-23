<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFotoToCarnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('carnets', function (Blueprint $table) {
        // Agregamos la columna foto después de num_carnet, permitiendo nulos
        $table->string('foto')->nullable()->after('num_carnet');
    });
}

public function down()
{
    Schema::table('carnets', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
}
}
