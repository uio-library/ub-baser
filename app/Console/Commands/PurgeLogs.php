<?php

namespace App\Console\Commands;

class PurgeLogs extends Command
{
    protected function logInfo($msg)
    {
        $this->info($msg);
        \Log::info($msg);
    }

    protected function logError($msg)
    {
        $this->error($msg);
        \Log::error($msg);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ub-baser:purge-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge old log entries.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channels = config('logging.channels.stack.channels');
        if (!in_array('postgres', $channels)) {
            return;
        }

        $days = (int) config('logging.channels.postgres.days');
        if ($days <= 0) {
            return;
        }

        $deleted = \DB::delete("DELETE FROM log_entries WHERE time < now() - interval '$days day'");
        if ($deleted > 0) {
            $this->logInfo("{$deleted} loggmeldinger eldre enn {$days} dager ble slettet automatisk.");
        } else {
            $this->info("Ingen loggmeldinger ble slettet");
        }
    }
}
