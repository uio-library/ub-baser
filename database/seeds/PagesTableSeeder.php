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
        DB::table('pages')->insert([
            [
                'layout' => 'layouts.litteraturkritikk',
                'slug' => 'norsk-litteraturkritikk/intro',
                'permission' => 'litteraturkritikk',

                'title' => '',
                'body' => '<p><em>Norsk litteraturkritikk</em>, tidligere kjent som <em>Beyerbasen</em>, er en bibliografi med over 20 000 innførsler som vedlikeholdes av Universitetsbiblioteket i Oslo. <a href="/norsk-litteraturkritikk/om">Les mer om bibliografien</a></p>',

                'updated_by' => 1,
                'created_at' => '2015-12-13 13:32:23',
                'updated_at' => '2015-12-13 13:32:23',
            ],[
                'layout' => 'layouts.litteraturkritikk',
                'slug' => 'norsk-litteraturkritikk/om',
                'permission' => 'litteraturkritikk',

                'title' => 'Om basen',
                'body' => '<h2>Om basen</h2><p>Blablaba. <a href="/norsk-litteraturkritikk/kilder">Kilder</a> - <a href="/norsk-litteraturkritikk/felter">Felter</a></p>',

                'updated_by' => 1,
                'created_at' => '2015-12-13 13:32:23',
                'updated_at' => '2015-12-13 13:32:23',
            ],[
                'layout' => 'layouts.litteraturkritikk',
                'slug' => 'norsk-litteraturkritikk/kilder',
                'permission' => 'litteraturkritikk',

                'title' => 'Kilder',
                'body' => '<h2>Kilder</h2><p>Test</p>',

                'updated_by' => 1,
                'created_at' => '2015-12-13 13:32:23',
                'updated_at' => '2015-12-13 13:32:23',
            ],[
                'layout' => 'layouts.litteraturkritikk',
                'slug' => 'norsk-litteraturkritikk/felter',
                'permission' => 'litteraturkritikk',

                'title' => 'Felter',
                'body' => '<h2>Felter</h2><p>Test</p>',

                'updated_by' => 1,
                'created_at' => '2015-12-13 13:32:23',
                'updated_at' => '2015-12-13 13:32:23',
            ],[
                'layout' => 'layouts.dommer',
                'slug' => 'dommer/intro',
                'permission' => 'dommer',

                'title' => '',
                'body' => '<h2>Dommers populærnavn</h2>
                    <p>
                        Dommenes populærnavn eller kallenavn er funnet i den juridiske litteraturen og i domsavsigelser hvor avgjørelsene er omtalt. Enkelte avgjørelser er oppført med flere populærnavn. Basen gir referanser til Norsk retstidende (Rt.), Rettens gang (RG) og Nordiske domme i sjøfartanliggende (ND). Basen utvikles av Juridisk bibliotek, Universitetet i Oslo
                    </p>
                    <p>
                        Forslag til dommer som du mener burde være med i basen, kan sendes til ujur@ub.uio.no med angivelse av i hvilken sammenheng det aktuelle populærnavnet har vært eller vil bli brukt.
                    </p>
                ',
                'updated_by' => 1,
                'created_at' => '2015-12-13 13:32:23',
                'updated_at' => '2015-12-13 13:32:23',
            ],
        ]);
    }
}
