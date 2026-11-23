<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Sala;

class SalasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sedes = DB::table('sedes')->orderBy('id')->pluck('id');

        $salas = [
            ['nombre' => 'Sala General', 'descripcion' => 'Sala general de libros'],
            ['nombre' => 'Sala Infantil', 'descripcion' => 'Sala de literatura infantil'],
            ['nombre' => 'Centro didáctico', 'descripcion' => 'Centro didáctico'],
            ['nombre' => 'Sala estadal', 'descripcion' => 'Sala estadal'],
            ['nombre' => 'Centro de Investigación', 'descripcion' => 'Centro de investigación'],
            ['nombre' => 'Administración', 'descripcion' => 'Administración'],
            ['nombre' => 'Presidencia', 'descripcion' => 'Presidencia'],
        ];

        $map = [
            'general'        => 'Sala General',
            'infantil'       => 'Sala Infantil',
            'didactico'      => 'Centro didáctico',
            'estadal'        => 'Sala estadal',
            'investigacion'  => 'Centro de Investigación',
            'administracion' => 'Administración',
            'precidencia'    => 'Presidencia',
        ];

        $sedePrincipal = $sedes->first();

        if ($sedePrincipal) {
            // Normaliza la sala "General" creada manualmente para que no
            // quede duplicada con "Sala General" en la misma sede.
            if (!Sala::where('sede_id', $sedePrincipal)->where('nombre', 'Sala General')->exists()) {
                Sala::where('sede_id', $sedePrincipal)
                    ->where('nombre', 'General')
                    ->update(['nombre' => 'Sala General']);
            }

            foreach ($salas as $sala) {
                Sala::firstOrCreate(
                    ['sede_id' => $sedePrincipal, 'nombre' => $sala['nombre']],
                    ['descripcion' => $sala['descripcion']]
                );
            }
        }

        // Asigna la sala maestra a los registros antiguos de cada sede
        // que guardaban el código del select en lugar del id.
        foreach ($sedes as $sedeId) {
            foreach ($map as $codigo => $nombre) {
                $sala = Sala::where('sede_id', $sedeId)
                    ->where('nombre', $nombre)
                    ->first();

                if (!$sala) {
                    continue;
                }

                DB::table('visitas')
                    ->where('sede_id', $sedeId)
                    ->where('departamento', $codigo)
                    ->whereNull('sala_id')
                    ->update(['sala_id' => $sala->id]);

                DB::table('visitas_guiadas')
                    ->where('sede_id', $sedeId)
                    ->where('tipo_recorrido', $codigo)
                    ->whereNull('sala_id')
                    ->update(['sala_id' => $sala->id]);
            }
        }
    }
}
