<?php

use App\Bases\Litteraturkritikk\Person as LitteraturkritikkPerson;
use App\Bases\Litteraturkritikk\Record as LitteraturkritikkRecord;
use App\Bases\Litteraturkritikk\RecordView as LitteraturkritikkRecordView;
use Illuminate\Database\Seeder;

class LitteraturkritikkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persons = factory(LitteraturkritikkPerson::class, 20)->create()
            ->pluck('id')
            ->toArray();

        factory(LitteraturkritikkRecord::class, 50)->create()
            ->each(function ($rec) use ($persons) {
                $forfatter = $persons[array_rand($persons)];
                $rec->persons()->attach($forfatter, ['person_role' => 'forfatter']);

                $kritiker = $persons[array_rand($persons)];
                $rec->persons()->attach($kritiker, ['person_role' => 'kritiker']);
            });

        LitteraturkritikkRecordView::refreshView();
    }
}
