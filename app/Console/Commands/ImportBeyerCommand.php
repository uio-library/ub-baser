<?php

namespace App\Console\Commands;

use App\BeyerKritikkType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Punic\Language;

class ImportBeyerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:beyer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Beyer data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fields = [
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

        $kritikktyper = [];
        foreach (BeyerKritikkType::all() as $kilde) {
            $kritikktyper[mb_strtolower($kilde->navn)] = $kilde->id;
        }

        $this->info('Importing Beyer');
        $data = require storage_path('beyer.data.php');
        $this->comment('Loaded ' . count($data) . ' records into memory.');
        if (count($fields) != count($data[0])) {
            $this->error('Expected ' . count($fields) . ' fields, got ' . count($data[0]) . ' fields.');

            return;
        }

        for ($i = 0; $i < count($data); $i++) {
            $row = [];
            for ($j = 0; $j < count($fields); $j++) {
                $row[$fields[$j]] = $data[$i][$j];
            }
            $data[$i] = $row;
        }

        $allespraak = array_flip(Language::getAll(true, true, 'nb'));
        $allespraak['finsk-svensk'] = 'sv';
        $allespraak['bokmål'] = 'nb';
        $allespraak['nynorsk'] = 'nn';
        $allespraak['bokmål (innslag av nynorsk)'] = 'nb';

        foreach ($data as &$row) {
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

            $lang = mb_strtolower($row['spraak']);
            $row['spraak'] = empty($lang) ? null : $allespraak[$lang];

            $verk_spraak = trim($row['verk_spraak']);
            if (empty($verk_spraak)) {
                $verk_spraak = [];
            } else {
                $verk_spraak = array_map(function ($t) {
                    return mb_strtolower(trim($t, '. ?()'));
                }, preg_split('/[,\/]/', $verk_spraak));
            }

            $verk_spraak = array_map(function ($lang) use ($allespraak, $row) {
                try {
                    return $allespraak[$lang];
                } catch (\Exception $e) {
                    $this->error('Unknown language: ' . $lang);
                    print_r($row);

                    return '??';
                }
            }, $verk_spraak);

            $row['verk_spraak'] = json_encode($verk_spraak);

            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        $chunks = array_chunk($data, 100);

        $this->comment('Clearing DB');
        \DB::delete('delete from beyer');

        $this->comment('Inserting rows');
        foreach ($chunks as $chunk) {
            \DB::table('beyer')->insert($chunk);
        }
        $this->info('Done');
    }
}
