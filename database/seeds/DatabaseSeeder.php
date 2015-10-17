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

        $this->call(BeyerTableSeeder::class);
        $this->call(DommerTableSeeder::class);
        $this->call(LetrasTableSeeder::class);
        $this->call(OpesTableSeeder::class);

        Model::reguard();
    }
}
