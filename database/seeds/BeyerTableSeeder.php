<?php

use Illuminate\Database\Seeder;

class BeyerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('beyer_kritikktyper')->insert([
            ['navn' => 'avhandling'],
            ['navn' => 'bloggpost'],
            ['navn' => 'bokanmeldelse'],
            ['navn' => 'debattinnlegg'],
            ['navn' => 'essay'],
            ['navn' => 'kronikk'],
            ['navn' => 'kåseri'],
            ['navn' => 'oversiktsartikkel'],
            ['navn' => 'samtaleprogram'],
            ['navn' => 'vitenskapelig artikkel'],
            ['navn' => 'annet'],

            ['navn' => 'dagskritikk'],
            ['navn' => 'debatt'],
            ['navn' => 'teaterkritikk'],
            ['navn' => 'forfatterportrett'],
            ['navn' => 'intervju'],
            ['navn' => 'nekrolog'],

            ['navn' => 'artikkel'],
            ['navn' => 'litteraturhistorie'],
            ['navn' => 'biografi'],

            // ['navn' => 'teaterkritikk'],
            // ['navn' => 'teaterkritikk'],
            // ['navn' => 'teaterkritikk'],
            // ['navn' => 'teaterkritikk'],
            // ['navn' => 'teaterkritikk'],
            // ['navn' => 'teaterkritikk'],

        ]);
    }
}
