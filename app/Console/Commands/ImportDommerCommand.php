<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportDommerCommand extends Command
{
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
        $data = require storage_path('import/dommer.data.php');

        foreach ($data as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();

            // $qq = $row['aar'] . '-' . $row['side'] . '-' . $row['kilde_id'];
            $qq = $row['navn'];

            if (isset($tmp[$qq])) {
                print $qq . ' : ' . $tmp[$qq][0] . ', ' . $row['navn'] . "\n";
            }
            // $tmp[$qq] = [$row['navn']];
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
        $this->comment('Clearing tables');
        $this->clearData();

        $this->comment('Filling dommer_kilder');
        $this->fillDommerKilderTable();

        $this->comment('Filling dommer');
        $this->fillDommerTable();

        $this->info('Done');
    }
}
