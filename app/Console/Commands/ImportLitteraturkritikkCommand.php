<?php

namespace App\Console\Commands;

use App\Bases\Litteraturkritikk\Person;
use App\Bases\Litteraturkritikk\Record;
use Illuminate\Support\Arr;
use Punic\Language;

class ImportLitteraturkritikkCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:litteraturkritikk';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check if tables are empty. Ask to empty them if not.
        if (!$this->ensureTablesEmpty([
            'litteraturkritikk_records',
            'litteraturkritikk_personer',
            'litteraturkritikk_record_person',
            'litteraturkritikk_kritikktyper',
        ])) {
            return;
        }

        // Import data from CSV files
        $this->importTabularFile('csv', $this->argument('folder'), 'litteraturkritikk_records.csv', 'litteraturkritikk_records');

        $this->importTabularFile('csv', $this->argument('folder'), 'litteraturkritikk_personer.csv', 'litteraturkritikk_personer');

        $this->importTabularFile('csv', $this->argument('folder'), 'litteraturkritikk_record_person.csv', 'litteraturkritikk_record_person');

        $this->importTabularFile('csv', $this->argument('folder'), 'litteraturkritikk_kritikktyper.csv', 'litteraturkritikk_kritikktyper');

        // Fix auto-incrementing sequences
        $this->updateSequence('litteraturkritikk_records', 'id');
        $this->updateSequence('litteraturkritikk_personer', 'id');
        $this->updateSequence('litteraturkritikk_record_person', 'id');
        $this->updateSequence('litteraturkritikk_kritikktyper', 'id');

        // Refresh views
        // $this->refreshView('litteraturkritikk_record_search');

        // Done!
        $this->comment('Import complete');
    }
}
