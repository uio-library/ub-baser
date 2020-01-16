<?php

namespace App\Console\Commands;

use ErrorException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'import';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['folder', InputArgument::REQUIRED, 'The folder to import data from'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Whether to delete existing data without asking', null],
        ];
    }

    protected function getForceArg()
    {
        $force = $this->option('force');

        if (env('APP_ENV') === 'production') {
            $this->error('This is the production environment! --force is not supported here!');
            $force = false;
        }

        return $force;
    }

    /**
     * Process a single value. This method can be overriden in an importer
     * to do some kind of processing before the data are inserted into the database,
     * e.g. converting a string value to a JSON value.
     *
     * @return array
     */
    protected function processValue(string $column, string $value)
    {
        return trim($value);
    }

    protected function readTsvFileHeader(string $filename)
    {
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new ErrorException('Failed to open file: ' . $filename);
        }
        $header = explode("\t", stream_get_line($handle, 1024 * 1024, "\n"));
        fclose($handle);
        return $header;
    }

    protected function readTsvFileRows(string $filename, array $columnNames)
    {
        // Note: We don't use the build-in csv_ functions in PHP because they don't allow us to
        // not use a quoting character. (We could have set the quoting character to some character
        // that is very unlikely to be encountered, but that's just stupid.)

        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new ErrorException('Failed to open file: ' . $filename);
        }

        // Skip header line
        stream_get_line($handle, 1024 * 1024, "\n");

        $lineNo = 0;
        while (($row = stream_get_line($handle, 1024 * 1024, "\n")) !== false) {
            $row = explode("\t", $row);
            $lineNo++;
            if (count($row) != count($columnNames)) {
                throw new ErrorException(sprintf(
                    "Row %d: Found %d columns, expected %d",
                    $lineNo,
                    count($row),
                    count($columnNames)
                ));
            }
            $indexedRow = [];
            foreach ($columnNames as $idx => $column) {
                $indexedRow[$column] = $this->processValue($column, $row[$idx]);
            }
            yield $indexedRow;
        }
        fclose($handle);
    }

    protected function importTsvFile(string $folder, string $filename, string $table)
    {
        $filename = rtrim($folder, '/') . '/' . ltrim($filename, '/');

        $this->comment("Importing rows from $filename into $table");

        $columns = $this->readTsvFileHeader($filename);

        $dbCols = \Schema::getColumnListing($table);
        foreach ($columns as $col) {
            if (!in_array($col, $dbCols)) {
                throw new ErrorException(sprintf(
                    "The column '%s' from %s was not found in the %s table",
                    $col,
                    $filename,
                    $table
                ));
            }
        }

        $buffer = [];
        $rows = 0;
        foreach ($this->readTsvFileRows($filename, $columns) as $row) {
            $rows++;
            $buffer[] = $row;
            if (count($buffer) > 1000) {
                \DB::table($table)->insert($buffer);
                $buffer = [];
            }
        }
        if (count($buffer) > 0) {
            \DB::table($table)->insert($buffer);
        }
        $this->comment("Imported $rows rows from $filename into $table");
    }

    protected function ensureTableEmpty(string $tableName, bool $force = false)
    {
        $count = \DB::table($tableName)->count();
        if ($count !== 0) {
            if ($force) {
                $this->warn("Deleting $count rows from $tableName");
            } elseif (!$this->confirm("The '$tableName' table is not empty! Delete the $count existing rows? [y|N]")) {
                return false;
            }
            \DB::table($tableName)->delete();
        }
        return true;
    }

    /**
     * Check if tables are empty. Ask to empty them if not (unless we are in the production environment).
     */
    protected function ensureTablesEmpty(array $tableNames)
    {
        $force = $this->getForceArg();

        foreach ($tableNames as $tableName) {
            if (!$this->ensureTableEmpty($tableName, $force)) {
                return false;
            }
        }
        return true;
    }

    protected function refreshView(string $name)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->comment('Refreshing view: ' . $name);
        \DB::unprepared('REFRESH MATERIALIZED VIEW ' . $name);
    }

    /**
     * Update a Postgres sequence for a column to match the maximum value of the column.
     */
    protected function updateSequence(string $table, string $column)
    {
        $table = filter_var($table, FILTER_SANITIZE_STRING);
        $column = filter_var($column, FILTER_SANITIZE_STRING);
        $this->comment("Updating sequence: $table.$column");
        \DB::unprepared(
            "SELECT pg_catalog.setval(pg_get_serial_sequence('$table', '$column'), MAX($column)) FROM $table"
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        $force = $this->getForceArg();

        $this->call('import:dommer', [
            '--force' => $force,
            'folder' => "$folder/dommer",
        ]);

        $this->call('import:letras', [
            '--force' => $force,
            'folder' => "$folder/letras",
        ]);

        $this->call('import:litteraturkritikk', [
            '--force' => $force,
            'folder' => "$folder/litteraturkritikk",
        ]);
    }
}
