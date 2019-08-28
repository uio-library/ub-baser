<?php

namespace App\Console\Commands;

use App\Dommer\DommerRecord;
use Carbon\Carbon;

class ImportDommerCommand extends ImportCommand
{
    protected $fields = [
        'navn',
        'aar',
        'side',
        'note',
        'kilde_id',
        'id',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:dommer {--force   : Whether to delete existing data without asking}
                                          {filename  : The JSON file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Dommer data';

    protected function fillDommerKilderTable()
    {
        $data = [
            ['id' => '1', 'navn' => 'Rettens gang'],
            ['id' => '2', 'navn' => 'Norsk retstidende'],
            ['id' => '3', 'navn' => 'Nordiske domme i sjøfartanliggende'],
        ];
        \DB::table('dommer_kilder')->insert($data);
    }

    protected function importDommerTable($filename)
    {
        $data = $this->getData($filename);
        if (\DB::table('dommer')->insert($data)) {
            $this->comment('Imported ' . count($data) . ' records');
        }
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

        if (!$this->ensureEmpty('dommer', $force)) {
            return;
        }
        if (!$this->ensureEmpty('dommer_kilder', $force)) {
            return;
        }

        // ------

        $this->fillDommerKilderTable();
        $this->importDommerTable($filename);

        $this->comment('Updating sequences');

        \DB::unprepared(
            "SELECT pg_catalog.setval(pg_get_serial_sequence('dommer', 'id'), MAX(id)) FROM dommer"
        );
        \DB::unprepared(
            "SELECT pg_catalog.setval(pg_get_serial_sequence('dommer_kilder', 'id'), MAX(id)) FROM dommer_kilder"
        );

        $this->comment('Import complete');
    }
}
