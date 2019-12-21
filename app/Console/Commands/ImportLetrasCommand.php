<?php

namespace App\Console\Commands;

class ImportLetrasCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:letras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Letras"';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check if tables are empty. Ask to empty them if not.
        if (!$this->ensureTablesEmpty(['letras'])) {
            return;
        }

        // Import data from TSV files
        $this->importTsvFile($this->argument('folder'), 'letras.tsv', 'letras');

        // Fix auto-incrementing sequences
        $this->updateSequence('letras', 'id');

        // Done!
        $this->comment('Import complete');
    }
}
