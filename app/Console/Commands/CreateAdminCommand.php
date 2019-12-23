<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin
                            {--y|yes : Automatic yes to prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default admin user, useful for development';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('This will create the user "admin@example.org" with password "secret".');
        if (!$this->option('yes') && !$this->confirm('Do you want to continue?')) {
            return;
        }

        $credentials = ['email' => 'admin@example.org'];

        $user = User::firstOrNew($credentials);
        if (!$user->isDirty()) {
            $this->info('User already exists');

            return;
        }

        $user->name = 'Georg Sverdrup';
        $user->rights = ['admin'];
        $user->password = bcrypt('secret');
        $user->save();

        $this->info('User created!');
    }
}
