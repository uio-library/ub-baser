<?php

namespace App\Console\Commands;

use Carbon\Carbon;

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
        $data = json_decode(file_get_contents(storage_path('import/letras.json'), true));
        $this->comment('Loaded ' . count($data) . ' records into memory.');

        $data = $this->mapToFields($data);

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
        $this->clearTable();

        $this->comment('Filling letras');
        $this->fillTable();

        $this->info('Done');
    }
}
