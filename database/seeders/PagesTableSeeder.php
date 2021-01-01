<?php

namespace Database\Seeders;

use App\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::factory()
            ->times(10)
            ->create();
    }
}
