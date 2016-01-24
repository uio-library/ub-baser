<?php

namespace App\Console\Commands;

use App\Litteraturkritikk\KritikkType;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\Record;
use Punic\Language;

class ImportLitteraturkritikkCommand extends ImportCommand
{

    protected $fields = [
        'id',
        'kritiker_etternavn',
        'kritiker_fornavn',
        'kritiker_kjonn',
        'kritiker_pseudonym',
        'kritiker_kommentar',
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
        'forfatter_etternavn',
        'forfatter_fornavn',
        'forfatter_kommentar',
        'forfatter_kjonn',
        'verk_tittel',
        'verk_utgivelsessted',
        'verk_aar',
        'verk_sjanger',
        'verk_spraak',
        'verk_kommentar',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:litteraturkritikk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for Norsk litteraturkritikk';

    public function normalizeVerkSpraak($value, $langCodes) {

        // Split by , or /
        $value = preg_split('/[,\/]/', $value);

        // Lowercase and trim .?()
        $value = array_map(function ($t) {
            return mb_strtolower(trim($t, '. ?()'));
        }, $value);

        // Filter out empty values
        $value = array_values(array_filter($value, function($x) {
            return !empty($x);
        }));

        // Map to language codes
        $value = array_map(function ($lang) use ($langCodes) {
            try {
                return $langCodes[$lang];
            } catch (\Exception $e) {
                $this->error('Unknown language code: ' . $lang);

                return '??';
            }
        }, $value);

        return json_encode($value);
    }


    function processRecordRow(&$row, $allespraak)
    {
        $typer = trim($row['kritikktype']);
        if (empty($typer)) {
            $typer = [];
        } else {
            $typer = array_map(function ($t) {
                return mb_strtolower(trim($t));
            }, explode(',', $typer));
        }
        // foreach ($typer as $t) {
        //     if (!isset($kritikktyper[$t])) {
        //         $this->warn('Unknown kritikktype: ' . $t);
        //     }
        // }
        $row['kritikktype'] = json_encode($typer);

        // Normalize 'spraak' as valid ISO639 language code
        $lang = mb_strtolower($row['spraak']);
        $row['spraak'] = empty($lang) ? null : $allespraak[$lang];

        // Normalize 'verk_spraak' as array of valid ISO639 language codes
        $row['verk_spraak'] = $this->normalizeVerkSpraak($row['verk_spraak'], $allespraak);
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
        $value = array_values(array_filter($value, function($x) {
            return !empty($x);
        }));

        return $value;
    }

    protected function extractMfl(&$row, $record, $column)
    {
        if (preg_match('/m\.\s?fl\./', $row[$column])) {
            $row[$column] = preg_replace('/m\.\s?fl\./', '', $row[$column]);
            $row[$column] = trim($row[$column]);
            $record->forfatter_mfl = true;
        }
    }

    protected function normalizeKjonn($value)
    {
        if (strtolower($value) == 'mann') return 'm';
        elseif (strtolower($value) == 'kvinne') return 'f';
        elseif (strtolower($value) == 'ukjent') return 'u';
        else return null;
    }

    public function processPerson(&$record, &$row, $role)
    {
        $rowId = $row['id'];
        $etternavn = $row[$role . '_etternavn'];
        $fornavn = $row[$role . '_fornavn'];
        $kjonn = $row[$role . '_kjonn'];
        $kommentar = $row[$role . '_kommentar'];

        $etternavn_arr = $this->splitTrimAndFilterEmpty($etternavn);
        $fornavn_arr = $this->splitTrimAndFilterEmpty($fornavn);

        $this->stats[$role][count($etternavn_arr)] = isset($this->stats[$role][count($etternavn_arr)])
            ? $this->stats[$role][count($etternavn_arr)] + 1
            : 1;

        if (count($etternavn_arr) > count($fornavn_arr)) {
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
                $this->error("[$rowId] Person $person->id har registrert flere verdier for kjønn: <$person->kjonn> og <$kjonn>");
            }
            $person->kjonn = $kjonn;

            if ($role == 'kritiker' && $red) {
                $this->error("[$rowId] Redaktørrolle angitt for kritiker");
            }
            $person_role = $red ? 'redaktør' : $role;
            $kommentar = !empty($kommentar) ? $kommentar : null;

            $record->persons()->save($person, [
                'person_role' => $person_role,
                'kommentar' => $kommentar,
            ]);
        }
    }

    public function processCreators(&$row) {
        $record = Record::findOrFail($row['id']);
        $record->persons()->detach();

        $record->forfatter_mfl = false;
        $record->kritiker_mfl = false;
        $this->extractMfl($row, $record, 'forfatter_etternavn');
        $this->extractMfl($row, $record, 'forfatter_fornavn');
        $this->extractMfl($row, $record, 'kritiker_etternavn');
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
        $kritikktyper = [];
        foreach (KritikkType::all() as $kilde) {
            $kritikktyper[mb_strtolower($kilde->navn)] = $kilde->id;
        }

        $this->info('');
        $this->warn(' This will re-populate the table from scratch. Any user contributed data will be lost!');
        if (!$this->confirm('Are you sure you want to continue? [y|N]')) {
            return;
        }

        $data = $this->getData('import/litteraturkritikk.json');

        $allespraak = array_flip(Language::getAll(true, true, 'nb'));
        $allespraak['finsk-svensk'] = 'sv';
        $allespraak['bokmål'] = 'nb';
        $allespraak['nynorsk'] = 'nn';
        $allespraak['bokmål (innslag av nynorsk)'] = 'nb';


        // Separate out 'person' columns
        $personColumns = [
            'forfatter_etternavn',
            'forfatter_fornavn',
            'forfatter_kjonn',
            'forfatter_kommentar',
            'kritiker_etternavn',
            'kritiker_fornavn',
            'kritiker_kjonn',
            'kritiker_kommentar',
            'kritiker_pseudonym',
        ];
        $persons = array_map(function($x) use ($personColumns) {
            return array_only($x, array_merge(['id'], $personColumns));
        }, $data);
        $records = array_map(function($x) use ($personColumns) {
            return array_except($x, $personColumns);
        }, $data);

        foreach ($records as &$row) {
            $this->processRecordRow($row, $allespraak);
        }

        $this->comment('Clearing DB');
        \DB::delete('delete from litteraturkritikk_records');

        $this->comment('Inserting records');

        $chunks = array_chunk($records, 100);
        foreach ($chunks as $chunk) {
            \DB::table('litteraturkritikk_records')->insert($chunk);
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

        $this->info('Refreshing views');

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        $this->info('Updating sequences');

        \DB::unprepared('SELECT pg_catalog.setval(pg_get_serial_sequence(\'litteraturkritikk_records\', \'id\'), MAX(id)) FROM litteraturkritikk_records');
        \DB::unprepared('SELECT pg_catalog.setval(pg_get_serial_sequence(\'litteraturkritikk_personer\', \'id\'), MAX(id)) FROM litteraturkritikk_personer');

        $this->info('Done');
    }

}
