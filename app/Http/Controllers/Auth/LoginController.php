<?php

namespace App\Http\Controllers\Auth;

use App\Bases\Dommer\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Slides\Saml2\Models\Tenant;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as protected localLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $defaultSaml2TenantKey = env('SAML2_DEFAULT_TENANT', 'uio-weblogin');
        $defaultSaml2Tenant = $defaultSaml2TenantKey ? Tenant::where('key', $defaultSaml2TenantKey)->first() : null;
        $defaultSaml2TenantUuid = $defaultSaml2Tenant ? $defaultSaml2Tenant->uuid : null;
        return view('auth.login', ['defaultSaml2TenantUuid'=> $defaultSaml2TenantUuid]);
    }

    public function samlError()
    {
        return view('auth.saml_error', [
            'error' => session()->get('saml2_error.last_error_reason', ''),
        ]);
    }

    public function samlRegister(Request $request)
    {
        return view('auth.saml_register', [
            'data' => $request->session()->get('saml_response'),
        ]);
    }

    public function samlStoreNewUser(Request $request)
    {
        $data = $request->session()->get('saml_response');

        if (!$data || !$data['saml_id']) {
            exit('no saml data');
        }

        $user = User::firstOrNew([
            'saml_id' => $data['saml_id'],
        ]);
        $user->name = $data['name'];
        $user->email = $data['email'];

        $user->last_login_at = Carbon::now();
        $user->saml_session = $data['saml_session'];

        $user->save();

        $this->log(
            'Opprettet ny bruker fra UiO-innlogging: <a href="%s">%s (%s)</a>.',
            action('Admin\UserController@show', $user->id),
            $user->name,
            $user->email
        );

        Auth::login($user);

        $request->session()->forget('saml_response');

        return redirect('/')->with('status', 'Velkommen til UB-baser!');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user->saml_session !== null) {
            $request->session()->put('url.intended', url()->previous());

            return redirect()->route('saml2_logout', 'uio');
        }

        return $this->localLogout($request);
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect(url()->previous());
    }
}
