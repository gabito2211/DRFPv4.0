<?php
// database/migrations/2026_08_15_000002_add_sede_encargado_to_actividades_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeEncargadoToActividadesTable extends Migration
{
    public function up()
    {
        Schema::table('actividades', function (Blueprint $table) {
            // Agregar sede_id - sedes.id es bigIncrements, usamos unsignedBigInteger
            if (!Schema::hasColumn('actividades', 'sede_id')) {
                $table->unsignedBigInteger('sede_id')->nullable()->after('sede');
                $table->foreign('sede_id')
                      ->references('id')
                      ->on('sedes')
                      ->onDelete('set null');
            }
            
            // Agregar encargado_id - encargados.id es increments, usamos unsignedInteger
            if (!Schema::hasColumn('actividades', 'encargado_id')) {
                $table->unsignedInteger('encargado_id')->nullable()->after('sede_id');
                $table->foreign('encargado_id')
                      ->references('id')
                      ->on('encargados')
                      ->onDelete('set null');
            }
        });

        // Migrar datos existentes de 'sede' (texto) a 'sede_id' (foreign key)
        try {
            $actividades = \App\Actividades::all();
            foreach ($actividades as $actividad) {
                if ($actividad->sede && !$actividad->sede_id) {
                    $sede = \App\Sede::where('biblioteca', $actividad->sede)
                                ->orWhere('comunidad', $actividad->sede)
                                ->first();
                    
                    if ($sede) {
                        $actividad->sede_id = $sede->id;
                        $actividad->save();
                    }
                }
            }
        } catch (\Exception $e) {
            // Si hay error, continuar
        }
    }

    public function down()
    {
        Schema::table('actividades', function (Blueprint $table) {
            if (Schema::hasColumn('actividades', 'sede_id')) {
                $table->dropForeign(['sede_id']);
                $table->dropColumn('sede_id');
            }
            
            if (Schema::hasColumn('actividades', 'encargado_id')) {
                $table->dropForeign(['encargado_id']);
                $table->dropColumn('encargado_id');
            }
        });
    }
}