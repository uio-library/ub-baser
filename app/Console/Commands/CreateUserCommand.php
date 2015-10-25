<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user
                            {email       : Email of the user}
                            {name        : Name of the user}
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
        if ($user->isDirty()) {
            $user->name = $this->argument('name');
            $user->rights = $this->option('admin') ? ['admin'] : [];
            $user->save();

            \Password::sendResetLink($credentials, function (Message $message) {
                $message->subject('Velkommen til ub-baser');
            });

            $this->info('User created, email sent to ' . $user->email . '.');
        } else {
            $this->info('User already exists');
        }
    }
}
