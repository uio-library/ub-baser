<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class DatabaseDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump
                            {--database= : Name of database in config file}
                            {--path=     : Path to destination file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump database for codeception testing';

    public function getMysqlCommand($config, $destinationFile)
    {
        $dumpCommand = 'mysqldump';

        return sprintf(
            '%s --user=%s --password=%s --host=%s --port=%s %s > %s',
            $dumpCommand,
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg(Arr::get($config, 'port', 3306)),
            escapeshellarg($config['database']),
            escapeshellarg($destinationFile)
        );
    }

    public function getPostgresCommand($config, $destinationFile)
    {
        $dumpCommand = 'pg_dump';

        return sprintf(
            'PGPASSWORD=%s %s -Fc --no-acl --no-owner --format=plain -h %s -U %s %s > %s',
            escapeshellarg($config['password']),
            $dumpCommand,
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($destinationFile)
        );
    }

    public function getCommand($destinationFile)
    {
        $conn = $this->option('database') ?: config('database.default');
        $config = config('database.connections.' . $conn);

        switch ($config['driver']) {
            case 'mysql':
                return $this->getMysqlCommand($config, $destinationFile);
            case 'pgsql':
                return $this->getPostgresCommand($config, $destinationFile);
            default:
                throw new \Exception('Unsupported database: ' . $conn);
        }
    }

    /**
     * The cleanup() method in codeception/src/Codeception/Lib/Driver/PostgreSql.php
     * won't cleanup custom operators, so we need to filter those out from the dump file.
     */
    public function filterOutOperatorDefinitions($filename)
    {
        $data = file_get_contents($filename);
        $data = preg_replace('/CREATE OPERATOR .*?;/s', '', $data);
        file_put_contents($filename, $data);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $destinationFile = $this->option('path') ?: base_path('tests/_data/dump.sql');

        $command = $this->getCommand($destinationFile);

        $process = new Process($command);
        $process->setTimeout(60);
        $process->run();
        if (!$process->isSuccessful()) {
            $this->error('Could not dump database to ' . $destinationFile);
            $this->error($process->getErrorOutput());

            return;
        }

        $this->filterOutOperatorDefinitions($destinationFile);
        $this->info('Database dumped successfully to ' . $destinationFile);
    }
}
