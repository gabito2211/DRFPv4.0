<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalaIdToVisitas extends Migration
{
    public function up()
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->unsignedInteger('sala_id')->nullable()->after('departamento');
        });

        Schema::table('visitas', function (Blueprint $table) {
            $table->foreign('sala_id')
                ->references('id')->on('salas')
                ->onDelete('set null');
        });

        Schema::table('visitas_guiadas', function (Blueprint $table) {
            $table->unsignedInteger('sala_id')->nullable()->after('tipo_recorrido');
        });

        Schema::table('visitas_guiadas', function (Blueprint $table) {
            $table->foreign('sala_id')
                ->references('id')->on('salas')
                ->onDelete('set null');
        });

        // El tipo de recorrido deja de ser un ENUM cerrado para poder
        // almacenar el nombre de la sala seleccionada desde el maestro.
        DB::statement("ALTER TABLE visitas_guiadas MODIFY tipo_recorrido VARCHAR(191) NOT NULL");
    }

    public function down()
    {
        DB::table('visitas_guiadas')
            ->whereNotIn('tipo_recorrido', ['general', 'investigacion', 'infantil', 'tecnico'])
            ->update(['tipo_recorrido' => 'general']);

        DB::statement("ALTER TABLE visitas_guiadas MODIFY tipo_recorrido ENUM('general','investigacion','infantil','tecnico') NOT NULL");

        Schema::table('visitas_guiadas', function (Blueprint $table) {
            $table->dropForeign(['sala_id']);
            $table->dropColumn('sala_id');
        });

        Schema::table('visitas', function (Blueprint $table) {
            $table->dropForeign(['sala_id']);
            $table->dropColumn('sala_id');
        });
    }
}
