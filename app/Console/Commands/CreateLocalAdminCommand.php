<?php

namespace App\Console\Commands;

use App\Base;
use App\User;
use Illuminate\Console\Command;

class CreateLocalAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ub-baser:create-local-admin
                            {--y|yes : Automatic yes to prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = 'admin@example.org';
        $password = 'secret';

        $this->info("This will create a local admin with user $username and password $password");
        $this->info("");
        $this->info("Please don't run this in production!");
        if (!$this->option('yes') && !$this->confirm('Do you want to continue?')) {
            return;
        }

        $credentials = ['email' => $username];

        $user = User::firstOrNew($credentials);
        if (!$user->isDirty()) {
            $this->info('User already exists');

            return;
        }
        $rights = Base::pluck('id');
        $rights[] = 'admin';

        $user->name = $username;
        $user->rights = $rights;
        $user->password = bcrypt($password);
        $user->save();
    }
}
