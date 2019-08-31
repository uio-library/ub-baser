<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Saml2Login
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Saml2LoginEvent $event
     * @param Request $request
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $data = $event->getSaml2User();
        $attrs = $data->getAttributes();

        $saml_id = $attrs['eduPersonPrincipalName'][0];
        $user = User::where('saml_id', '=', $saml_id)->first();

        if ($user !== null) {
            $user->last_login_at = Carbon::now();
            $user->save();
            return \Auth::login($user);
        }

        $this->request->session()->put('saml_response', [
            'saml_id' =>  $saml_id,
            'saml_session' => $data->getSessionIndex(),
            'name' => $attrs['cn'][0],
            'email' => $attrs['mail'][0],
        ]);
    }
}
