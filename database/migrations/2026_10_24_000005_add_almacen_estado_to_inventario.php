<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddAlmacenEstadoToInventario extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE inventario MODIFY estado ENUM('bueno','deteriorado','almacen') NOT NULL");
    }

    public function down()
    {
        DB::table('inventario')
            ->where('estado', 'almacen')
            ->update(['estado' => 'bueno']);

        DB::statement("ALTER TABLE inventario MODIFY estado ENUM('bueno','deteriorado') NOT NULL");
    }
}
