<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(LitteraturkritikkTableSeeder::class);
        $this->call(DommerTableSeeder::class);
        $this->call(LetrasTableSeeder::class);
        $this->call(OpesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
}
