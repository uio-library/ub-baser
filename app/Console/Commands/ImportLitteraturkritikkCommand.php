<?php

namespace App\Console\Commands;

use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\Record;
use Illuminate\Support\Arr;
use Punic\Language;

class ImportLitteraturkritikkCommand extends ImportCommand
{

    protected $fields = [
        'id',

        // 1. Kritikken

        // 1.1 Person

        'kritiker_etternavn',
        'kritiker_fornavn',
        'kritiker_kjonn',
        'kritiker_pseudonym',
        'kritiker_kommentar',

        // 1.2 Dokument

        'kritikktype',
        'publikasjon',
        'utgivelsessted',
        'aar',
        'dato',
        'aargang',
        'nummer',
        'bind',
        'hefte',
        'sidetall',
        'utgivelseskommentar',
        'tittel',
        'spraak',
        'kommentar',

        // 2. Verket

        // 2.1 Person

        'forfatter_etternavn',
        'forfatter_fornavn',
        'forfatter_kommentar',
        'forfatter_kjonn',

        // 2.2 Dokument

        'verk_tittel',
        'verk_utgivelsessted',
        'verk_dato',
        'verk_sjanger',
        'verk_spraak',
        'verk_kommentar',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:litteraturkritikk  {--force   : Whether to delete existing data without asking}
                                                      {filename  : The JSON file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for Norsk litteraturkritikk';

    protected function processKritikktype($input)
    {
        $out = trim($input);
        if (empty($out)) {
            $out = [];
        } else {
            $out = array_map(function ($t) {
                return mb_strtolower(trim($t));
            }, explode(',', $out));
        }
        // foreach ($out as $t) {
        //     if (!isset($kritikktyper[$t])) {
        //         $this->warn('Unknown kritikktype: ' . $t);
        //     }
        // }
        return json_encode($out);
    }

    protected function mergeYearDate($aar, $dato, $kommentar)
    {
        $months = [
            'jan' => '01',
            'feb' => '02',
            'mar' => '03',
            'apr' => '04',
            'mai' => '05',
            'jun' => '06',
            'jul' => '07',
            'aug' => '08',
            'sep' => '09',
            'okt' => '10',
            'nov' => '11',
            'des' => '12',
        ];

        $in = "'$aar' '$dato'";
        $dato_out = $aar;
        $kommentar_out = $kommentar;

        if ($dato) {
            if (preg_match('/^([0-9]+)\. ?([a-z]{3})/', $dato, $matches)) {
                if (isset($months[$matches[2]])) {
                    $dato_out .= sprintf('-%02d-%02d', $months[$matches[2]], $matches[1]);
                } else {
                    $this->error('Invalid month: ' . $matches[2]);
                }
            } elseif (preg_match('/^([a-z]{3})/', $dato, $matches)) {
                if (isset($months[$matches[1]])) {
                    $dato_out .= sprintf('-%02d', $months[$matches[1]]);
                } else {
                    $this->error('Invalid month: ' . $matches[1]);
                }
            } else {
                if (empty($kommentar_out)) {
                    $kommentar_out = 'Publiseringsdato: ' . $dato;
                } else {
                    $kommentar_out .= '. Publiseringsdato: ' . $dato;
                }
                $this->error('Dato: ' . $dato . '. Kommentar: ' . $kommentar_out);
            }
        }

        if ($in !== "'$dato_out'") {
            //$this->info("$in → $dato_out");
        }

        return [$dato_out, $kommentar_out];
    }


    protected function processRecordRow(&$record)
    {
        $record['kritikktype'] = $this->processKritikktype($record['kritikktype']);

        list($record['dato'], $record['kommentar']) = $this->mergeYearDate(
            $record['aar'],
            $record['dato'],
            $record['kommentar']
        );

        unset($record['aar']);

        // Normalize 'spraak' as valid ISO639 language code
//        $lang = mb_strtolower($record['spraak']);
//        $record['spraak'] = empty($lang) ? null : $allespraak[$lang];

        // Normalize 'verk_spraak' as array of valid ISO639 language codes
//        $record['verk_spraak'] = $this->normalizeVerkSpraak($record['verk_spraak'], $allespraak);

        // Trim all fields
        foreach (array_keys($record) as $k) {
            $record[$k] = trim($record[$k]);
            if (empty($record[$k])) {
                $record[$k] = null;
            }
        }
    }

    public function splitTrimAndFilterEmpty($value)
    {
        // Split
        $value = preg_split('/(,\s?|\s+og\s+)/', $value);

        // Trim
        $value = array_map(function ($t) {
            return trim($t);
        }, $value);

        // Filter out empty values
        $value = array_values(array_filter($value, function ($x) {
            return !empty($x);
        }));

        return $value;
    }

    protected function extractMfl(&$row, $record, $column)
    {
        if (preg_match('/m\.?\s?fl\./', $row[$column])) {
            $oldValue = $row[$column];
            $row[$column] = preg_replace('/m\.?\s?fl\./', '', $row[$column]);
            $row[$column] = trim($row[$column]);
            $this->info(sprintf('Replaced "%s" → "%s"', $oldValue, $row[$column]));
            return true;
        }
        return false;
    }

    protected function normalizeKjonn($value)
    {
        if (strtolower($value) == 'mann') {
            return 'm';
        } elseif (strtolower($value) == 'kvinne') {
            return 'f';
        } elseif (strtolower($value) == 'ukjent') {
            return 'u';
        } else {
            return null;
        }
    }

    public function processPerson(&$record, &$row, $role)
    {
        $rowId = $row['id'];

        $etternavn = $row[$role . '_etternavn'];
        $fornavn = $row[$role . '_fornavn'];
        $kjonn = $row[$role . '_kjonn'];

        $kommentar = Arr::get($row, $role . '_kommentar');
        $pseudonym  = Arr::get($row, $role . '_pseudonym');

        $etternavn_arr = $this->splitTrimAndFilterEmpty($etternavn);
        $fornavn_arr = $this->splitTrimAndFilterEmpty($fornavn);

        $this->stats[$role][count($etternavn_arr)] = isset($this->stats[$role][count($etternavn_arr)])
            ? $this->stats[$role][count($etternavn_arr)] + 1
            : 1;

        if (count($etternavn_arr) > count($fornavn_arr) && count($fornavn_arr) != 0) {
            if (!in_array($etternavn_arr[0], ['anonym', 'ukjent', 'Ukjent'])) {
                $this->warn("[$rowId] Antall etternavn <$etternavn> er flere enn antallet fornavn <$fornavn>.");
            }
        }

        if (count($etternavn_arr) < count($fornavn_arr)) {
            $this->error("[$rowId] Antall etternavn <$etternavn> er færre enn antallet fornavn <$fornavn>.");
        }

        for ($i = 0; $i < count($etternavn_arr); $i++) {
            if (!isset($fornavn_arr[$i])) {
                $fornavn_arr[$i] = null;
            }

            if (strtolower($etternavn_arr[$i] == 'ukjent')) {
                $etternavn_arr[$i] = 'ukjent';
                $fornavn_arr[$i] = null;
            }

            $red = false;
            if (preg_match('/\s?\(red\.\)/', $etternavn_arr[$i])) {
                $etternavn_arr[$i] = preg_replace('/\s?\(red\.\)/', '', $etternavn_arr[$i]);
                $red = true;
            }
            if (preg_match('/\s?\(red\.\)/', $fornavn_arr[$i])) {
                $fornavn_arr[$i] = preg_replace('/\s?\(red\.\)/', '', $fornavn_arr[$i]);
                $red = true;
            }

            $person = Person::firstOrNew([
                'etternavn' => $etternavn_arr[$i],
                'fornavn' => $fornavn_arr[$i],
            ]);

            $kjonn = $this->normalizeKjonn($kjonn);
            if ($person->kjonn && $kjonn && $person->kjonn != $kjonn) {
                $this->error(
                    "[$rowId] Person {$person->id} ({$person->etternavn}, {$person->fornavn}) " .
                    "har registrert flere verdier for kjonn: <$person->kjonn> og <$kjonn>"
                );
            }
            $person->kjonn = $kjonn;

            if ($role == 'kritiker' && $red) {
                $this->error("[$rowId] Redaktørrolle angitt for kritiker");
            }
            $person_role = $red ? 'redaktør' : $role;

            $record->persons()->save($person, [
                'person_role' => $person_role,
                'kommentar' => $kommentar,
                'pseudonym' => $pseudonym,
            ]);
        }
    }

    public function processCreators(&$row)
    {
        $record = Record::findOrFail($row['id']);
        $record->persons()->detach();

        $record->verk_forfatter_mfl = $this->extractMfl($row, $record, 'forfatter_etternavn') ||
            $this->extractMfl($row, $record, 'forfatter_fornavn');
        $record->kritiker_mfl = $this->extractMfl($row, $record, 'kritiker_etternavn') ||
            $this->extractMfl($row, $record, 'kritiker_fornavn');

        $this->processPerson($record, $row, 'forfatter');
        $this->processPerson($record, $row, 'kritiker');

        $record->save();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = $this->argument('filename');
        $force = $this->option('force');

        $this->comment('');
        $this->comment(sprintf("Preparing import at host '%s'", \DB::getConfig('host')));

        if (env('APP_ENV') === 'production') {
            $this->error('This is the production environment!!!');
            $force = false;
        }

        if (!$this->ensureEmpty('litteraturkritikk_records', $force)) {
            return;
        }

        // ------

        $data = $this->getData($filename);

//        $allespraak = array_flip(Language::getAll(true, true, 'nb'));
//        $allespraak['finsk-svensk'] = 'sv';
//        $allespraak['bokmål'] = 'nb';
//        $allespraak['nynorsk'] = 'nn';
//        $allespraak['bokmål (innslag av nynorsk)'] = 'nb';


        // Separate out 'person' columns
        $personColumns = [
            'forfatter_etternavn',
            'forfatter_fornavn',
            'forfatter_kjonn',
            'forfatter_kommentar',  // pivot-egenskap

            'kritiker_etternavn',
            'kritiker_fornavn',
            'kritiker_kjonn',
            'kritiker_pseudonym',    // pivot-egenskap
            'kritiker_kommentar',     // pivot-egenskap
        ];
        $persons = array_map(function ($x) use ($personColumns) {
            return Arr::only($x, array_merge(['id'], $personColumns));
        }, $data);

        $records = array_map(function ($x) use ($personColumns) {
            return Arr::except($x, $personColumns);
        }, $data);

        foreach ($records as &$record) {
            $this->processRecordRow($record);
        }

        $this->comment('Inserting records');

        $chunks = array_chunk($records, 1000);
        foreach ($chunks as $chunk) {
            if (\DB::table('litteraturkritikk_records')->insert($chunk)) {
                $this->comment('Imported ' . count($chunk) . ' records');
            }
        }

        $this->comment('Inserting persons');

        $this->stats = ['forfatter' => [], 'kritiker' => []];

        foreach ($persons as &$row) {
            $this->processCreators($row);
        }
        foreach ($this->stats['forfatter'] as $k => $v) {
            print "Forfatter: $k : $v\n";
        }
        foreach ($this->stats['kritiker'] as $k => $v) {
            print "Kritiker: $k : $v\n";
        }

        $this->comment('Refreshing views');

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        $this->comment('Updating sequences');

        \DB::unprepared(
            "SELECT pg_catalog.setval(pg_get_serial_sequence('litteraturkritikk_records', 'id'), MAX(id))" .
            " FROM litteraturkritikk_records"
        );
        \DB::unprepared(
            "SELECT pg_catalog.setval(pg_get_serial_sequence('litteraturkritikk_personer', 'id'), MAX(id))" .
            " FROM litteraturkritikk_personer"
        );

        $this->comment('Import complete');
    }
}
