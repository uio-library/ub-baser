<?php

namespace App\Console\Commands;

use ErrorException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class ImportCommand extends Command
{
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
        $value = trim($value);
        if ($value === 'TRUE') return 1;
        if ($value === 'FALSE') return 0;
        if ($value === '') return null;

        return $value;
    }

    protected function importTabularFile(string $format, string $folder, string $filename, string $table)
    {
        $filename = rtrim($folder, '/') . '/' . ltrim($filename, '/');

        $this->comment("Importing rows from $filename into $table");

        $columns = $this->readFileHeader($format, $filename);

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

        $overrides = [
            'created_by' => 1,
            'updated_by' => 1,
        ];

        $buffer = [];
        $rows = 0;
        foreach ($this->readFileRows($format, $filename, $columns) as $row) {
            $rows++;
            foreach ($overrides as $k => $v) {
                if (isset($row[$k])) {
                    $row[$k] = $v;
                }
            }
            $buffer[] = $row;
            if (count($buffer) > 1000) {
                \DB::table($table)->insert($buffer);
                $this->comment("Imported 1000 rows");
                $buffer = [];
            }
        }
        if (count($buffer) > 0) {
            \DB::table($table)->insert($buffer);
        }
        $this->comment("Imported $rows rows from $filename into $table");
    }

    protected function readFileHeader(string $format, string $filename)
    {
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new ErrorException('Failed to open file: ' . $filename);
        }
        $header = $this->readRow($format, $handle);
        fclose($handle);
        return $header;
    }

    protected function readRow(string $format, $handle)
    {
        if ($format === 'tsv') {
            // Note: We don't use the build-in csv_ functions in PHP because they don't allow us to
            // not use a quoting character. (We could have set the quoting character to some character
            // that is very unlikely to be encountered, but that's just stupid.)
            $line = stream_get_line($handle, 1024 * 1024, "\n");
            return explode("\t", $line);
        }

        return fgetcsv($handle);
    }

    protected function readFileRows(string $format, string $filename, array $columnNames)
    {
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new ErrorException('Failed to open file: ' . $filename);
        }

        // Skip header line
        stream_get_line($handle, 1024 * 1024, "\n");

        $lineNo = 0;
        while (($row = $this->readRow($format, $handle)) !== false) {
            $lineNo++;
            if (count($row) != count($columnNames)) {
                throw new ErrorException(sprintf(
                    'Row %d: Found %d columns, expected %d',
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
        return $this->importTabularFile('tsv', $folder, $filename, $table);
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
}
