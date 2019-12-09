<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LogoutEvent;

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
        $user = \Auth::user();
        if ($user) {
            $user->saml_session = null;
            $user->save();
        }
        \Auth::logout();
        \Session::save();
    }
}
