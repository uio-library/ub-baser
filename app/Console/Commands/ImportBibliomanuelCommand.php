<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ImportBibliomanuelCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:bibliomanuel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Bibliografi om Manuel"';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check if tables are empty. Ask to empty them if not.
        if (!$this->ensureTablesEmpty(['bibliomanuel'])) {
            return;
        }

        // Import data from TSV files
        $folder = $this->argument('folder');
        $this->importTsvFile($folder, 'bibliomanuel.tsv', 'bibliomanuel');

        // Done!
        $this->comment('Import complete');
    }
}
