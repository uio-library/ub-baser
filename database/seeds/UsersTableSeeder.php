<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Georg Sverdrup',
            'email' => 'admin@example.org',
            'password' => bcrypt('secret'),
            'rights' => '["admin","beyer","letras","dommer"]',
        ]);
    }
}
