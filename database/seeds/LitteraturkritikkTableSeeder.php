<?php

use App\Bases\Litteraturkritikk\Person as LitteraturkritikkPerson;
use App\Bases\Litteraturkritikk\Record as LitteraturkritikkRecord;
use App\Bases\Litteraturkritikk\RecordView as LitteraturkritikkRecordView;
use App\Bases\Litteraturkritikk\Work as LitteraturkritikkWork;
use App\Bases\Litteraturkritikk\Schema;
use App\Bases\Litteraturkritikk\WorkSchema;
use Illuminate\Database\Seeder;

class LitteraturkritikkTableSeeder extends Seeder
{

    public function getRandomFromArray(array $arr, int $count): array
    {
        $out = [];
        if ($count === 0) {
            return [];
        }
        if ($count === 1) {
            return [$arr[array_rand($arr, 1)]];
        }
        return array_values(
            array_map(
                function ($k) use ($arr) {
                    return $arr[$k];
                },
                array_rand($arr, $count)
            )
        );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schema = new Schema();
        $possibleValues = [
            'kritikktype' => array_map(
                // multiple
                function ($x) {
                    return [$x['value']];
                },
                $schema->keyed()['kritikktype']->values
            ),
            'medieformat' => array_map(
                // single
                function ($x) {
                    return $x['value'];
                },
                $schema->keyed()['medieformat']->values
            ),
        ];

        $workSchema = new WorkSchema();
        $possibleValuesWork = [
            'verk_sjanger' => array_map(
                // single
                function ($x) {
                    return $x['value'];
                },
                $workSchema->keyed()['verk_sjanger']->values
            ),
        ];

        $persons = factory(LitteraturkritikkPerson::class, 20)
            ->create()
            ->pluck('id')
            ->toArray();

        $works = factory(LitteraturkritikkWork::class, 20)
            ->create()
            ->each(function ($work, $key) use ($persons, $possibleValuesWork) {
                foreach ($possibleValuesWork as $k => $v) {
                    $work->{$k} = $v[$key % count($v)];
                }

                $authors = $this->getRandomFromArray($persons, rand(1, 5));
                for ($i=0; $i < count($authors); $i++) {
                    $work->forfattere()->attach($authors[$i], [
                        'person_role' => ['forfatter'],
                        'position' => $i + 1,
                    ]);
                }
            })
            ->pluck('id')
            ->toArray();

        factory(LitteraturkritikkRecord::class, 50)
            ->make()
            ->each(function ($rec, $key) use ($persons, $works, $possibleValues) {
                // For controlled fields, assign from allowed values. For the tests, we need at least
                // one of the first value, so we don't assign completely randomly.
                foreach ($possibleValues as $k => $v) {
                    $rec->{$k} = $v[$key % count($v)];
                }
                $rec->save();

                $critics = $this->getRandomFromArray($persons, 1);
                $rec->kritikere()->attach($critics[0], [
                    'person_role' => ['kritiker'],
                    'position' => 1,
                ]);

                $subjectWorks = $this->getRandomFromArray($works, rand(0, 3));
                for ($i=0; $i < count($subjectWorks); $i++) {
                    $rec->subjectWorks()->attach($subjectWorks[$i], [
                        'position' => $i + 1,
                    ]);
                }

                $subjectPersons = $this->getRandomFromArray($persons, rand(0, 3));
                for ($i=0; $i < count($subjectPersons); $i++) {
                    $rec->subjectPersons()->attach($subjectPersons[$i], [
                        'position' => $i + 1,
                    ]);
                }
            });

        LitteraturkritikkRecordView::refreshView();
    }
}
