<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\User;
use Carbon\Carbon;

class Saml2Login
{
    /**
     * Handle the event.
     *
     * @param  Saml2LoginEvent  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $data = $event->getSaml2User();
        $uid = $data->getUserId();
        $attrs = $data->getAttributes();

        dd($attrs);

        $user = User::firstOrNew([
            'saml_id' => $uid,
        ]);

        $user->name = $attrs['FirstName'][0] . ' ' . $attrs['LastName'][0];
        $user->email = $attrs['EmailAddress'][0];
        $user->last_login_at = Carbon::now();
        $user->saml_session = $data->getSessionIndex();

        $user->save();

        \Auth::login($user);
    }
}
