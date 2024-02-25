<?php

namespace App\Listeners;

use Slides\Saml2\Events\SignedIn;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @param SignedIn        $event
     * @param Request         $request
     *
     * @return void
     */
    public function handle(SignedIn $event)
    {
        $data = $event->getSaml2User();
        $attrs = $data->getAttributes();

        $saml_id = $attrs['eduPersonPrincipalName'][0];
        $user = User::where('saml_id', '=', $saml_id)->first();

        if ($user !== null) {
            $user->last_login_at = Carbon::now();
            $user->save();

            return Auth::login($user);
        }

        $this->request->session()->put('saml_response', [
            'saml_id' =>  $saml_id,
            'saml_session' => $data->getSessionIndex(),
            'name' => $attrs['cn'][0],
            'email' => $attrs['mail'][0],
        ]);
    }
}
