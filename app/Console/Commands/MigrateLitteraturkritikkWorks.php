<?php

namespace App\Console\Commands;

use App\Bases\Litteraturkritikk\Person;
use App\Bases\Litteraturkritikk\Record;
use App\Bases\Litteraturkritikk\Work;
use Illuminate\Console\Command;
use Symfony\Component\VarDumper\VarDumper;

class MigrateLitteraturkritikkWorks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:lkworks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getAuthorsMentioned($record)
    {
        return \DB::table('litteraturkritikk_record_person')
            ->where('record_id', '=', $record->id)
            ->where('person_role', '!=', 'kritiker')
            ->join('litteraturkritikk_personer', function ($join) {
                $join->on('litteraturkritikk_personer.id', '=', 'litteraturkritikk_record_person.person_id');
            })
            ->get();
    }

    protected function migrateCritics($record)
    {
        $critics = \DB::table('litteraturkritikk_record_person')
            ->where('record_id', '=', $record->id)
            ->where('person_role', '=', 'kritiker')
            ->join('litteraturkritikk_personer', function ($join) {
                $join->on('litteraturkritikk_personer.id', '=', 'litteraturkritikk_record_person.person_id');
            })
            ->get();

        foreach ($critics as $critic) {
            \DB::table('litteraturkritikk_person_contributions')
                ->insert([
                    'contribution_type' => Record::class,
                    'contribution_id' => $record->id,
                    'person_id' => $critic->person_id,
                    'person_role' => $critic->person_role,
                    'kommentar' => $critic->kommentar,
                    'pseudonym' => $critic->pseudonym,
                    'position' => $critic->position,
                ]);
        }
    }

    protected function getWorksMentioned($rec, $contributions): array
    {
        $workMfl = false;
        $workTitles = explode('; ', $rec->verk_tittel);
        if ($workTitles[count($workTitles) - 1] == 'mfl.') {
            $workMfl = true;
            array_pop($workTitles);
        }
        $workProps = [
            'verk_dato',
            'verk_sjanger',
            'verk_spraak',
            'verk_kommentar',
            'verk_utgivelsessted',
            'verk_forfatter_mfl',
            'verk_fulltekst_url',
        ];

        $works = [];
        $workCount = count($workTitles);
        for ($n = 0; $n < $workCount; $n++) {
            $work = [
                'verk_tittel' => $workTitles[$n]
            ];
            foreach ($workProps as $prop) {
                if (is_null($rec->{$prop})) {
                    $work[$prop] = null;
                } else {
                    if (is_bool($rec->{$prop})) {
                        $work[$prop] = $rec->{$prop};
                    } else {
                        $values = explode('; ', $rec->{$prop});
                        if (count($values) == count($workTitles)) {
                            $work[$prop] = $values[$n];
                        } elseif (count($values) == 1) {
                            $work[$prop] = $values[0];
                        } elseif ($workCount === 1) {
                            // Use value as-is. Example: https://ub-baser.uio.no/norsk-litteraturkritikk/record/2736 (verk_utgivelsessted)
                            $work[$prop] = $rec->{$prop};
                        } else {
                            $propCount = count($values);
                            $this->error("RECORD {$rec->id}: Number of $prop ($propCount) does not match number of works ($workCount)");
                        }
                    }
                }
            }
            if (count($contributions) == count($workTitles)) {
                $work['contribs'] = [$contributions[$n]];
            } elseif (count($contributions) == 1) {
                $work['contribs'] = [$contributions[0]];
            } elseif (count($workTitles) == 1) {
                $work['contribs'] = $contributions;
            } else {
                $authorCount = count($contributions);
                $this->error("RECORD {$rec->id}: Number of authors/editors ($authorCount) does not match number of works ($workCount)");
            }
            $works[] = $work;
        }

        return [
            'works' => $works,
            'mfl' => $workMfl,
        ];
    }

    protected function findWork(array $work)
    {
        $query = \DB::table('litteraturkritikk_works')
            ->where([
                'verk_tittel' => $work['verk_tittel'],
                'verk_dato' => $work['verk_dato'],
                'verk_spraak' => $work['verk_spraak'],
            ])
            ->select('litteraturkritikk_works.id as work_id');
        if (count($work['contribs'])) {
            $query
                ->join('litteraturkritikk_person_contributions', function ($join) {
                    $join->on('litteraturkritikk_person_contributions.contribution_id', '=', 'litteraturkritikk_works.id')
                        ->where('litteraturkritikk_person_contributions.contribution_type', '=', 'App\Bases\Litteraturkritikk\Work');
                })
                ->join('litteraturkritikk_personer', function ($join) use ($work) {
                    $join->on('litteraturkritikk_personer.id', '=', 'litteraturkritikk_person_contributions.person_id')
                        ->where('litteraturkritikk_personer.etternavn', '=', $work['contribs'][0]->etternavn);
                });
        }
        return $query->first();
    }

    protected function findOrCreateWork($workData): int
    {
        $work = $this->findWork($workData);
        if (!is_null($work)) {
            return $work->work_id;
        }
        $work = $this->createWork($workData);
        return $work->id;
    }

    protected function addWorkReference($rec, $work_id, $position = 1)
    {
        \DB::table('litteraturkritikk_subject_work')->insertOrIgnore([
            [
                'record_id' => $rec->id,
                'work_id' => $work_id,
                'position' => $position,
            ]
        ]);

        $this->info("- Added reference: Record {$rec->id} discusses work {$work_id}");
    }

    protected function addAuthorshipReference($rec, $person_id, $position)
    {
        \DB::table('litteraturkritikk_subject_person')->insertOrIgnore([
            [
                'record_id' => $rec->id,
                'person_id' => $person_id,
                'position' => $position,
            ]
        ]);

        $this->info("- Added reference: Record {$rec->id} discusses person {$person_id}");
    }

    protected function createWork($data)
    {
        $insertData = [
            'verk_tittel' => $data['verk_tittel'],
            'verk_dato' => $data['verk_dato'],
            'verk_sjanger' => $data['verk_sjanger'],
            'verk_spraak' => $data['verk_spraak'],
            'verk_kommentar' => $data['verk_kommentar'],
            'verk_utgivelsessted' => $data['verk_utgivelsessted'],
            'verk_forfatter_mfl' => $data['verk_forfatter_mfl'],
            'verk_fulltekst_url' => $data['verk_fulltekst_url'],
        ];
        // VarDumper::dump($insertData);

        $work = Work::create($insertData);

        foreach ($data['contribs'] as $contrib) {
            \DB::table('litteraturkritikk_person_contributions')
                ->insert([
                    'contribution_type' => Work::class,
                    'contribution_id' => $work->id,
                    'person_id' => $contrib->person_id,
                    'person_role' => $contrib->person_role,
                    'kommentar' => $contrib->kommentar,
                    'pseudonym' => $contrib->pseudonym,
                    'position' => $contrib->position,
                ]);
        }

        $this->info('- Created work ' . $work->id);

        return $work;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrated = 0;

        foreach (Record::orderBy('id')->get() as $rec) {
        // foreach (\DB::table('litteraturkritikk_records')->orderBy('id')->get() as $rec) {

            // Migrate critics
            $this->migrateCritics($rec);

            // Get authors/editors
            $contributions = $this->getAuthorsMentioned($rec);

            // Get works
            $works = $this->getWorksMentioned($rec, $contributions);
            $workCount = count($works['works']);

            if ($workCount) {
                // This record discusses one or more works
                $this->info("Record {$rec->id} discusses {$workCount} work(s)");
                for ($n = 0; $n < $workCount; $n++) {
                    $work_id = $this->findOrCreateWork($works['works'][$n]);
                    $this->addWorkReference($rec, $work_id, $n + 1);
                }
                if ($works['mfl']) {
                    // List of works discussed is incomplete
                    $rec->discusses_more = true;
                    $rec->save();
                }
            } elseif (count($contributions)) {
                // This record discusses one or more authorships
                foreach ($contributions as $contribution) {
                    $this->addAuthorshipReference($rec, $contribution->person_id, $contribution->position);
                }
            } else {
                // @TODO
                // This record discusses some other stuff
                // $this->addTopicReference($rec, ???);
            }
//            if ($migrated++ > 10000) {
//                break;
//            }
        }
    }
}
