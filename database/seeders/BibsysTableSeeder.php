<?php

namespace Database\Seeders;

use App\Bases\Bibsys\BibsysDokument;
use App\Bases\Bibsys\BibsysObjekt;
use App\Bases\Bibsys\BibsysView;
use Illuminate\Database\Seeder;

class BibsysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BibsysObjekt::factory()
            ->times(10)
            ->create()
            ->each(function (BibsysObjekt $rec) {
                $n = rand(1, 3);
                for ($i = 0; $i <= $n; $i++) {
                    $rec->dokumentPoster()->save(
                        BibsysDokument::factory()->make()
                    );
                }
            });

        // Legg til seriedokid for en del dokumenter
        $dokids = BibsysDokument::pluck('dokid');
        foreach (BibsysDokument::get() as $rec) {
            $n = rand(0, 2);
            if ($n === 0) {
                $rec->seriedokid = $dokids->random();
                $rec->save();

                $rel = BibsysDokument::find($rec->seriedokid);
                $rel->har_hefter = true;
                $rel->save();
            }
        }

        BibsysView::refreshView();
    }
}
