<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SedesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sedes')->insert([
            [
                'id'         => 1,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'Biblioteca Dr. Félix Pifano',
                'direccion'  => 'esquina de la Calle 13 con Avenida 2, dentro del Complejo Cultural Andrés Bello en San Felipe, Estado Yaracuy.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'comunidad'  => 'san-felipe' ,
                'biblioteca' => 'S.L Luis Alberto Principal',
                'direccion'  => 'Avenida libertador entre Calle 5 y 6, Sede del ateneo "Cirilo Menoza" Marin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 3,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'S.L San Javier',
                'direccion'  => 'Calle principal, Entre Calle 1 y 2 Casa de la Cultura San Juan Javier',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 4,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'B.C Moral y Luces',
                'direccion'  => 'Casa de la Cultura, Comunnidad Valle Verde Trasnversal 3 Entre Calle 9 y 10 Transversal 3 entres calles 9 y 10',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 5,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'B.C Manuelita Sáenz',
                'direccion'  => 'Urb. Juan José de Maya, manzana 4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 6,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'B.C Ciudadela',
                'direccion'  => 'Av. Robert Serra con Av. Williams Lara. Ciudadela',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 7,
                'comunidad'  => 'san-felipe',
                'biblioteca' => 'B.C Higuerón',
                'direccion'  => 'Calle principal C.C. Higuerón',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 8,
                'comunidad'  => 'pena',
                'biblioteca' => 'B.P Soc. Oswaldo Méndez',
                'direccion'  => 'Calle 19 esquina carrera 8 y 9 Yaritagua',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 9,
                'comunidad'  => 'pena',
                'biblioteca' => 'S.L Simón Rodríguez',
                'direccion'  => 'Av. principal Las Velas casa vacacional. Las Velas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 10,
                'comunidad'  => 'pena',
                'biblioteca' => 'B.C El Palmar',
                'direccion'  => 'Calle principal, centro integral comunitario',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 11,
                'comunidad'  => 'bolivar',
                'biblioteca' => 'B.P Josefa Marín de Narváez',
                'direccion'  => 'Calle Sucre, frente a la plaza Bolívar. Aroa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 12,
                'comunidad'  => 'bolivar',
                'biblioteca' => 'P.P Ingrid Fernández',
                'direccion'  => 'Calle principal, sector El Cruce, comunidad Quebrada Honda',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 13,
                'comunidad'  => 'nirgua',
                'biblioteca' => 'B.P Rómulo Gallegos',
                'direccion'  => 'Av. 5 entre calles 8 y 9 frente a la plaza Bolívar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 14,
                'comunidad'  => 'nirgua',
                'biblioteca' => 'S.L Euclides Herrera',
                'direccion'  => 'Casa de la cultura "Euclides Herrera" frente a la plaza Bolívar de Salom',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 15,
                'comunidad'  => 'cocorote',
                'biblioteca' => 'Biblioteca Publica Simon Rodriguez',
                'direccion'  => 'centro de la calle 7 entre Av 6 y 5',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 16,
                'comunidad'  => 'paez',
                'biblioteca' => 'B.P José Alfonso Daza',
                'direccion'  => 'Casa de la cultura "Alberto Beto Ramírez" carrera 4 esquina calle 9. Sector El Silencio',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 17,
                'comunidad'  => 'paez',
                'biblioteca' => 'S.L Arturo Uslar Pietri',
                'direccion'  => 'Frente a la plaza Bolívar comunidad La Blanquera',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 18,
                'comunidad'  => 'independencia',
                'biblioteca' => 'S.L Celsa Mendoza Reyes',
                'direccion'  => 'Prolongación calle 39, módulo de funda comunal. Sabaneta',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 19,
                'comunidad'  => 'veroes',
                'biblioteca' => 'S.L Simón Rodríguez',
                'direccion'  => 'Calle Ecuador, al lado de la plaza Bolívar comunidad Casimiro Vásquez',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 20,
                'comunidad'  => 'aristides-bastidas',
                'biblioteca' => 'B.P Julia Rosa de López',
                'direccion'  => 'Casa de la cultura Arístides Bastidas calle principal sector centro. San Pablo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 21,
                'comunidad'  => 'manuel-monge',
                'biblioteca' => 'B.P Simón Bolívar',
                'direccion'  => 'Casa de cultura Alí Primera urb. El Centro II Yumare',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 22,
                'comunidad'  => 'urachiche',
                'biblioteca' => 'B.P Domingo Oviedo Parada',
                'direccion'  => 'Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 23,
                'comunidad'  => 'sucre',
                'biblioteca' => 'B.P Mercedes Cordido',
                'direccion'  => 'Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 24,
                'comunidad'  => 'bruzual',
                'biblioteca' => 'B.P Riben Suárez Moreno',
                'direccion'  => 'Calle 11 cruce Av. 5 casa de cultura José Parra Chivacoa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 26,
                'comunidad'  => 'bruzual',
                'biblioteca' => 'B.C Poa Poa',
                'direccion'  => 'Calle principal diagonal a la escuela, comunidad Poa Poa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

