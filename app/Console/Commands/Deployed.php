<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Deployed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ub-baser:deployed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post-deploy hook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $version = env('APP_VERSION');
        \Log::info("Oppdaterte UB-baser til versjon {$version}");
    }
}
