<?php

namespace App\Providers;

use Slides\Saml2\Events\SignedIn;
use Slides\Saml2\Events\SignedOut;
use App\Listeners\Saml2Login;
use App\Listeners\Saml2Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SignedIn::class => [
            Saml2Login::class,
        ],
        SignedOut::class => [
            Saml2Logout::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
