<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LogoutEvent;
use App\User;
use Carbon\Carbon;

class Saml2Logout
{
    /**
     * Handle the event.
     *
     * @param  Saml2LogoutEvent  $event
     * @return void
     */
    public function handle(Saml2LogoutEvent $event)
    {
        \Log::info('Received SAML logout event.');
        // die('Got logout event');

        $user = \Auth::user();
        if ($user) {
            $user->saml_session = null;
            $user->save();
        }
        \Auth::logout();
        \Session::save();
    }
}
