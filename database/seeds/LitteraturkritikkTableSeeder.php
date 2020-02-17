<?php

use App\Bases\Litteraturkritikk\Person as LitteraturkritikkPerson;
use App\Bases\Litteraturkritikk\Record as LitteraturkritikkRecord;
use App\Bases\Litteraturkritikk\RecordView as LitteraturkritikkRecordView;
use App\Bases\Litteraturkritikk\Schema;
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
        $schema = new Schema();
        $possibleValues = [
            'verk_sjanger' => array_map(
                // single
                function($x) { return $x['value']; },
                $schema->keyed()['verk_sjanger']->values
            ),
            'kritikktype' => array_map(
                // multiple
                function($x) { return [$x['value']]; },
                $schema->keyed()['kritikktype']->values
            ),
            'medieformat' => array_map(
                // single
                function($x) { return $x['value']; },
                $schema->keyed()['medieformat']->values
            ),
        ];

        $persons = factory(LitteraturkritikkPerson::class, 20)
            ->create()
            ->pluck('id')
            ->toArray();

        factory(LitteraturkritikkRecord::class, 50)
            ->make()
            ->each(function ($rec, $key) use ($persons, $possibleValues) {
                // For controlled fields, assign from allowed values. For the tests, we need at least
                // one of the first value, so we don't assign completely randomly.
                foreach ($possibleValues as $k => $v) {
                    $rec->{$k} = $v[$key % count($v)];
                }
                $rec->save();

                $forfatter = $persons[array_rand($persons)];
                $rec->persons()->attach($forfatter, ['person_role' => ['forfatter']]);

                $kritiker = $persons[array_rand($persons)];
                $rec->persons()->attach($kritiker, ['person_role' => ['kritiker']]);
            });

        LitteraturkritikkRecordView::refreshView();
    }
}
