<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {--force : Whether to delete existing data without asking}
                                   {folder  : The folder to import}';

    protected $fields = [];

    public function mapToFields($data)
    {
        $nRecords = count($data);
        $nFields = count($this->fields);

        if ($nFields != count($data[0])) {
            throw new \ErrorException('Expected ' . $nFields . ' fields, got ' . count($data[0]) . ' fields.');
        }

        for ($i = 0; $i < $nRecords; $i++) {
            $row = [];
            for ($j = 0; $j < $nFields; $j++) {
                $row[$this->fields[$j]] = $data[$i][$j];
            }
            $data[$i] = $row;
        }

        return $data;
    }

    public function getData($filename)
    {
        $data = json_decode(file_get_contents($filename));
        $this->comment('Read ' . count($data) . ' records from ' . $filename);

        $data = $this->mapToFields($data);

        foreach ($data as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        return $data;
    }

    protected function ensureEmpty(string $tableName, bool $force = false)
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        $force = $this->option('force');

        if (env('APP_ENV') === 'production') {
            $this->error('This is the production environment!!!');
            $force = false;
        }

        $this->call('import:dommer', [
            '--force' => $force,
            'filename' => "$folder/dommer.json",
        ]);

        $this->call('import:letras', [
            '--force' => $force,
            'filename' => "$folder/letras.json",
        ]);

        $this->call('import:litteraturkritikk', [
            '--force' => $force,
            'filename' => "$folder/litteraturkritikk.json",
        ]);
    }
}
