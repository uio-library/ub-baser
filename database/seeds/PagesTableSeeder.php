<?php

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
        DB::table('pages')->insert([[

            'name' => 'litteraturkritikk.intro',
            'layout' => 'layouts.litteraturkritikk',
            'route' => 'norsk-litteraturkritikk/intro',
            'permission' => 'litteraturkritikk',

            'title' => '',
            'body' => '<p><em>Norsk litteraturkritikk</em>, tidligere kjent som <em>Beyerbasen</em>, er en bibliografi med over 20 000 innførsler som vedlikeholdes av Universitetsbiblioteket i Oslo. <a href="/om">Les mer om bibliografien</a>.</p>',

            'updated_by' => 1,
            'created_at' => '2015-12-13 13:32:23',
            'updated_at' => '2015-12-13 13:32:23',
        ],[

            'name' => 'litteraturkritikk.about',
            'layout' => 'layouts.litteraturkritikk',
            'route' => 'norsk-litteraturkritikk/om',
            'permission' => 'litteraturkritikk',

            'title' => 'Om basen',
            'body' => '<h2>Om basen</h2><p>Blablaba. <a href="/norsk-litteraturkritikk/kilder">Kilder</a> - <a href="/norsk-litteraturkritikk/felter">Felter</a></p>',

            'updated_by' => 1,
            'created_at' => '2015-12-13 13:32:23',
            'updated_at' => '2015-12-13 13:32:23',
        ],[

            'name' => 'litteraturkritikk.sources',
            'layout' => 'layouts.litteraturkritikk',
            'route' => 'norsk-litteraturkritikk/kilder',
            'permission' => 'litteraturkritikk',

            'title' => 'Kilder',
            'body' => '<h2>Kilder</h2><p>Test</p>',

            'updated_by' => 1,
            'created_at' => '2015-12-13 13:32:23',
            'updated_at' => '2015-12-13 13:32:23',
        ],[

            'name' => 'litteraturkritikk.fields',
            'layout' => 'layouts.litteraturkritikk',
            'route' => 'norsk-litteraturkritikk/felter',
            'permission' => 'litteraturkritikk',

            'title' => 'Felter',
            'body' => '<h2>Felter</h2><p>Test</p>',

            'updated_by' => 1,
            'created_at' => '2015-12-13 13:32:23',
            'updated_at' => '2015-12-13 13:32:23',
        ]]);
    }
}
