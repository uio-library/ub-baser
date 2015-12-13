<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportLetrasCommand extends Command
{
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

    protected function clearData()
    {
        \DB::delete('delete from letras');
    }

    protected function fillLetrasTable()
    {
        $fields = [
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

        $this->info('Importing Letras');
        $data = require storage_path('import/letras.data.php');
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

        foreach ($data as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }
        \DB::table('letras')->insert($data);
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
        $this->clearData();

        $this->comment('Filling letras');
        $this->fillLetrasTable();

        $this->info('Done');
    }
}
