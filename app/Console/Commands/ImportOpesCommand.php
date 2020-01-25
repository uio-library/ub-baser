<?php

namespace App\Console\Commands;

class ImportOpesCommand extends ImportCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import:opes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for "Opes"';

    /**
     * Process a single value. This method can be overriden in an importer
     * to do some kind of processing before the data are inserted into the database,
     * e.g. converting a string value to a JSON value.
     *
     * @return array
     */
    protected function processValue(string $column, string $value)
    {
        $value = trim($value, " \t\n\r\0\x0B\"");

        switch ($column) {
            case 'persons':
            case 'subj_headings':
                $value = explode(';', $value);
                $value = array_map(function($x) { return trim($x); }, $value);
                $value = json_encode($value);
                break;

            case 'negative_in_copenhagen':
                $value = (substr($value, 0, 3) === 'Yes') ? '1' : '0';
                break;

            case 'date1':
            case 'date2':
                if ($value !== '') {
                    //$value = preg_replace('/\/.*$/', '', $value);
                    //$value = preg_replace('/\(\?\)/', '', $value);
                    if (!is_numeric($value)) {
                        echo "Warning: '$column' is not numeric: $value\n";
                    }
                }
                break;

            case 'date_cataloged':
                if ($value !== '') {
                    $value = explode('.', $value);
                    if (count($value) != 3) {
                        print("INVALID DATE: " . implode('.', $value));
                        die;
                    }
                    $value = sprintf('%s-%s-%s', $value[2], $value[1], $value[0]);
                }
                break;

            case 'ddbdp_pmichcitation':
                $value = strtolower(str_replace(':', ';', $value));
                break;
        }

        if (empty($value)) {
            $value = null;
        }

        return $value;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check if tables are empty. Ask to empty them if not.
        if (!$this->ensureTablesEmpty(['opes_publications', 'opes'])) {
            return;
        }

        // Import data from TSV files
        $this->importTsvFile($this->argument('folder'), 'opes.tsv', 'opes');

        // Import data from TSV files
        $this->importTsvFile($this->argument('folder'), 'opes_publications.tsv', 'opes_publications');

        // Fix auto-incrementing sequences
        $this->updateSequence('opes', 'id');
        $this->updateSequence('opes_publications', 'id');

        // Refresh views
        $this->refreshView('opes_view');

        // Done!
        $this->comment('Import complete');
    }
}
