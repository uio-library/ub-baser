<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateSamlUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ub-baser:create-saml-user
                            {email       : Email of the user}
                            {--admin     : Set flag to give user admin rights}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $credentials = ['email' => $this->argument('email')];

        $user = User::firstOrNew($credentials);
        if (!$user->isDirty()) {
            $this->info('User already exists');

            return;
        }

        $user->name = $this->argument('email');
        $user->saml_id = $this->argument('email');
        $user->rights = $this->option('admin') ? ['admin'] : [];
        $user->save();

        $this->info('SAML user ' . $user->email . ' created.');
    }
}
