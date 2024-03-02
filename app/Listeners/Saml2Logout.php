<?php

namespace App\Listeners;

use Slides\Saml2\Events\SignedOut;

class Saml2Logout
{
    /**
     * Handle the event.
     *
     * @param SignedOut $event
     *
     * @return void
     */
    public function handle(SignedOut $event)
    {
        $redirectTo = session('url.intended');
        $user = auth()->user();
        if ($user) {
            $user->saml_session = null;
            $user->save();
        }
        auth()->logout();
        session()->invalidate();
        session()->put('url.intended', $redirectTo);
    }
}
