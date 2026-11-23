<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAutoresTable extends Migration
{
    public function up()
    {
        Schema::create('autores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Backfill: se cargan como autores todos los nombres que ya existían
        // sueltos en inventario.autor, para que los libros registrados antes
        // de este cambio sigan mostrando su autor correctamente en el select
        // de edición (en vez de aparecer en blanco).
        if (Schema::hasTable('inventario')) {
            $autoresExistentes = DB::table('inventario')
                ->whereNotNull('autor')
                ->where('autor', '!=', '')
                ->distinct()
                ->pluck('autor');

            // Solución: Usar Carbon directamente ya que now() no existe en Laravel 5.4
            $ahora = \Carbon\Carbon::now();

            foreach ($autoresExistentes as $nombre) {
                $nombre = trim($nombre);
                if ($nombre === '') {
                    continue;
                }

                // Laravel 5.4 no tiene insertOrIgnore() (llegó en 5.8+),
                // así que se verifica manualmente antes de insertar por si
                // dos cotas distintas terminan con el mismo nombre recortado.
                $yaExiste = DB::table('autores')->where('nombre', $nombre)->exists();

                if (!$yaExiste) {
                    DB::table('autores')->insert([
                        'nombre'     => $nombre,
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('autores');
    }
}