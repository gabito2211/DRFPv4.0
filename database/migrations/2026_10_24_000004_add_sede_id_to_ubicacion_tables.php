<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSedeIdToUbicacionTables extends Migration
{
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropUnique('salas_nombre_unique');
        });

        Schema::table('salas', function (Blueprint $table) {
            $table->unsignedBigInteger('sede_id')->nullable()->after('id');
            $table->index('sede_id');
        });

        Schema::table('salas', function (Blueprint $table) {
            $table->unique(['sede_id', 'nombre']);
            $table->foreign('sede_id')
                ->references('id')->on('sedes')
                ->onDelete('set null');
        });

        Schema::table('pasillos', function (Blueprint $table) {
            $table->unsignedBigInteger('sede_id')->nullable()->after('sala_id');
            $table->index('sede_id');
        });

        Schema::table('pasillos', function (Blueprint $table) {
            $table->foreign('sede_id')
                ->references('id')->on('sedes')
                ->onDelete('set null');
        });

        Schema::table('estanterias', function (Blueprint $table) {
            $table->unsignedBigInteger('sede_id')->nullable()->after('pasillo_id');
            $table->index('sede_id');
        });

        Schema::table('estanterias', function (Blueprint $table) {
            $table->foreign('sede_id')
                ->references('id')->on('sedes')
                ->onDelete('set null');
        });

        $sedeId = DB::table('sedes')->orderBy('id')->value('id');

        if ($sedeId) {
            DB::table('salas')->whereNull('sede_id')->update(['sede_id' => $sedeId]);
        }

        DB::statement('UPDATE pasillos p JOIN salas s ON p.sala_id = s.id SET p.sede_id = s.sede_id');
        DB::statement('UPDATE estanterias e JOIN pasillos p ON e.pasillo_id = p.id SET e.sede_id = p.sede_id');
    }

    public function down()
    {
        Schema::table('estanterias', function (Blueprint $table) {
            $table->dropForeign(['sede_id']);
        });

        Schema::table('estanterias', function (Blueprint $table) {
            $table->dropColumn('sede_id');
        });

        Schema::table('pasillos', function (Blueprint $table) {
            $table->dropForeign(['sede_id']);
        });

        Schema::table('pasillos', function (Blueprint $table) {
            $table->dropColumn('sede_id');
        });

        Schema::table('salas', function (Blueprint $table) {
            $table->dropForeign(['sede_id']);
        });

        Schema::table('salas', function (Blueprint $table) {
            $table->dropUnique(['sede_id', 'nombre']);
        });

        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn('sede_id');
        });

        $duplicados = DB::table('salas')
            ->select('nombre')
            ->groupBy('nombre')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicados as $duplicado) {
            $ids = DB::table('salas')
                ->where('nombre', $duplicado->nombre)
                ->orderBy('id')
                ->pluck('id');

            $ids->shift();

            foreach ($ids as $id) {
                DB::table('salas')
                    ->where('id', $id)
                    ->update(['nombre' => $duplicado->nombre . ' #' . $id]);
            }
        }

        Schema::table('salas', function (Blueprint $table) {
            $table->unique('nombre');
        });
    }
}
