<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SedesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SalasTableSeeder::class);
        $this->call(AutoresTableSeeder::class);
    }
}
