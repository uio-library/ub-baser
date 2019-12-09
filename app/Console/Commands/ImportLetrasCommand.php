<?php

namespace App\Console\Commands;

class ImportLetrasCommand extends ImportCommand
{
    protected $fields = [
        'id',
        'forfatter',
        'land',
        'tittel',
        'utgivelsesaar',
        'sjanger',
        'oversetter',
        'tittel2',
        'utgivelsessted',
        'utgivelsesaar2',
        'forlag',
        'foretterord',
        'spraak',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:letras  {--force : Whether to delete existing data without asking}
                                           {filename  : The JSON file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Letras data';

    protected function importFile(string $filename)
    {
        $rows = $this->getData($filename);

        // Trim all values
        $rows = array_map(function ($row) {
            return array_map(function ($col) {
                return trim($col);
            }, $row);
        }, $rows);

        if (\DB::table('letras')->insert($rows)) {
            $this->comment('Imported ' . count($rows) . ' records');
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

        if (!$this->ensureEmpty('letras', $force)) {
            return;
        }

        // -------------

        $this->comment('Importing letras');
        $this->importFile($filename);

        $this->comment('Updating sequences');

        \DB::unprepared('SELECT pg_catalog.setval(pg_get_serial_sequence(\'letras\', \'id\'), MAX(id)) FROM letras');

        $this->comment('Import complete');
    }
}
