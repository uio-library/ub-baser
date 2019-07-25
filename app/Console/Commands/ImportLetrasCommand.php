<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;

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
    protected $signature = 'import:letras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Letras data';

    protected function clearTable()
    {
        \DB::delete('delete from letras');
    }

    protected function fillTable()
    {
        $rows = $this->getData('import/letras.json');

        // Trim all values
        $rows = array_map(function($row) {
            return array_map(function ($col) {
                return trim($col);
            }, $row);
        }, $rows);

        \DB::table('letras')->insert($rows);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('');
        $this->warn(' This will re-populate the table from scratch. Any user contributed data will be lost!');
        if (!$this->confirm('Are you sure you want to continue? [y|N]')) {
            return;
        }

        $this->comment('Clearing tables');
        $this->clearTable();

        $this->comment('Filling letras');
        $this->fillTable();

        $this->info('Updating sequences');

        \DB::unprepared('SELECT pg_catalog.setval(pg_get_serial_sequence(\'letras\', \'id\'), MAX(id)) FROM letras');

        $this->info('Done');

    }
}
