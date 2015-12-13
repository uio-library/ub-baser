<?php

namespace App\Console\Commands;

use App\BeyerKritikkType;
use Carbon\Carbon;
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $kritikktyper = [];
        foreach (BeyerKritikkType::all() as $kilde) {
            $kritikktyper[mb_strtolower($kilde->navn)] = $kilde->id;
        }

        $this->info('');
        $this->warn(' This will re-populate the table from scratch. Any user contributed data will be lost!');
        if (!$this->confirm('Are you sure you want to continue? [y|N]')) {
            return;
        }

        $this->info('Importing Norsk litteraturkritikk');

        $data = json_decode(file_get_contents(storage_path('import/litteraturkritikk.json'), true));
        $this->comment('Loaded ' . count($data) . ' records into memory.');

        $data = $this->mapToFields($data);

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
        \DB::delete('delete from litteraturkritikk');

        $this->comment('Inserting rows');
        foreach ($chunks as $chunk) {
            \DB::table('litteraturkritikk')->insert($chunk);
        }
        $this->info('Done');
    }
}
