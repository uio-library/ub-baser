<?php

namespace App\Console\Commands;

class ImportDommerCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:dommer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Dommers populærnavn"';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check if tables are empty. Ask to empty them if not.
        if (!$this->ensureTablesEmpty(['dommer', 'dommer_kilder'])) {
            return;
        }

        // Import data from TSV files
        $folder = $this->argument('folder');
        $this->importTsvFile($folder, 'dommer_kilder.tsv', 'dommer_kilder');
        $this->importTsvFile($folder, 'dommer.tsv', 'dommer');

        // Refresh views
        $this->refreshView('dommer_view');

        // Fix auto-incrementing sequences
        $this->updateSequence('dommer_kilder', 'id');
        $this->updateSequence('dommer', 'id');

        // Done!
        $this->comment('Import complete');
    }
}
