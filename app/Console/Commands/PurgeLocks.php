<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeLocks extends Command
{
    protected function logInfo($msg)
    {
        $this->info($msg);
        \Log::info($msg);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ub-baser:purge-locks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge stale locks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deleted = \DB::delete("DELETE FROM locks WHERE updated_at < now() - interval '1 hour'");
        if ($deleted > 0) {
            $this->logInfo("{$deleted} låste sider ble låst opp.");
        }
    }
}
