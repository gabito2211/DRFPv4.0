<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuariosBase = array(
            [
                'name'       => 'Administrador General',
                'email'      => 'admin@gmail.com',
                'password'   => bcrypt('drfp2026'),
                'rol'        => 1,
                'sede_id'    => 1, // Acceso global
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Recepcion General',
                'email'      => 're@gmail.com',
                'password'   => bcrypt('redrfp2026'),
                'rol'        => 2,
                'sede_id'    => 1, // Acceso global
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Almacenista General',
                'email'      => 'al@gmail.com',
                'password'   => bcrypt('aldrfp2026'),
                'rol'        => 1,
                'sede_id'    => 3, // Acceso global
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'cocorote',
                'email'      => 'cocorote@gmail.com',
                'password'   => bcrypt('12345678'),
                'rol'        => 1,
                'sede_id'    => 15, // Asignado a la Sede 2
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        );

        // Evitar duplicados si el seeder se ejecuta más de una vez
        foreach ($usuariosBase as $usuario) {
            if (DB::table('users')->where('email', $usuario['email'])->exists()) {
                continue;
            }
            DB::table('users')->insert($usuario);
        }

        // Un administrador por cada sede registrada.
        // Contraseña única para todos: 12345678
        $sedes = DB::table('sedes')->orderBy('id')->get();

        foreach ($sedes as $sede) {
            $email = 'admin' . str_pad($sede->id, 2, '0', STR_PAD_LEFT) . '@drfp.com';
            $nombreBiblioteca = $sede->biblioteca ? $sede->biblioteca : ('Sede ' . $sede->id);

            // Evitar duplicados si el seeder se ejecuta más de una vez
            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            DB::table('users')->insert([
                'name'       => 'Admin ' . $nombreBiblioteca,
                'email'      => $email,
                'password'   => bcrypt('12345678'),
                'rol'        => 1,
                'sede_id'    => $sede->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
