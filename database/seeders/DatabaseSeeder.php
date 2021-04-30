<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        $this->call(UsersTableSeeder::class);

        $this->call(LitteraturkritikkTableSeeder::class);
        $this->call(DommerTableSeeder::class);
        $this->call(LetrasTableSeeder::class);
        $this->call(BibliomanuelTableSeeder::class);
        $this->call(BibliofremmespraakTableSeeder::class);
        $this->call(OpesTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(BibsysTableSeeder::class);

        Model::reguard();
    }
}
