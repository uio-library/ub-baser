<?php

namespace App\Console\Commands;

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
    protected $signature = 'import:dommer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Dommer data';

    protected function clearData()
    {
        \DB::delete('delete from dommer');
        \DB::delete('delete from dommer_kilder');
    }

    protected function fillDommerKilderTable()
    {
        $data = [
            ['id' => '1', 'navn' => 'Rettens gang'],
            ['id' => '2', 'navn' => 'Norsk retstidende'],
            ['id' => '3', 'navn' => 'Nordiske domme i sjøfartanliggende'],
        ];
        \DB::table('dommer_kilder')->insert($data);
    }

    protected function fillDommerTable()
    {
        $this->info('Importing Dommer');

        $data = json_decode(file_get_contents(storage_path('import/dommer.json'), true));
        $this->comment('Loaded ' . count($data) . ' records into memory.');

        $data = $this->mapToFields($data);

        foreach ($data as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        \DB::table('dommer')->insert($data);
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

        $this->comment('Filling dommer_kilder');
        $this->fillDommerKilderTable();

        $this->comment('Filling dommer');
        $this->fillDommerTable();

        $this->info('Done');
    }
}
