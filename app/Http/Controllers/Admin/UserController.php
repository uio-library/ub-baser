<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\AuthServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'users' => User::all(),
        ];

        return response()->view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'rights' => AuthServiceProvider::$gateLabels,
        ];

        return response()->view('admin.user.create', $data);
    }

    /**
     * Return an array of rights from checkbox states.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function rightsFromRequest(Request $request)
    {
        $rights = [];
        foreach (AuthServiceProvider::listGates() as $gate) {
            if ($request->get('right-' . $gate)) {
                $rights[] = $gate;
            }
        }

        return $rights;
    }

    /**
     * Store a newly created user, or update an existing one.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return User
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $user = $id === null ? new User() : User::findOrFail($id);

        $this->validate($request, [
            'email' => 'required|email:rfc|unique:users,email' . ($id === null ? '' : ',' . $id) . '|max:255',
            'name'  => 'required|max:255',
            'saml_id' => 'nullable|email:rfc|unique:users,saml_id' . ($id === null ? '' : ',' . $id) . '|max:255',
        ]);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->saml_id = $request->get('saml_id');
        $user->rights = $this->rightsFromRequest($request);

        $user->save();

        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->updateOrCreate($request);

        $this->log(
            'Opprettet ny bruker: <a href="%s">%s (%s)</a>.',
            action('Admin\UserController@show', $user->id),
            $user->name,
            $user->email
        );

        \Password::sendResetLink(['email' => $user->email], function (Message $message) {
            $message->subject('Velkommen til ub-baser');
        });

        return redirect()->action('Admin\UserController@index')
            ->with('status', 'En epost er sendt til brukeren med instruksjoner for å sette passord.');
    }

    /**
     * Show a specific resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'user'   => $user,
            'rights' => AuthServiceProvider::$gateLabels,
        ];

        return response()->view('admin.user.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'user'   => $user,
            'rights' => AuthServiceProvider::$gateLabels,
        ];

        return response()->view('admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->updateOrCreate($request, $id);

        $this->log(
            'Oppdaterte bruker: <a href="%s">%s (%s)</a>.',
            action([UserController::class, 'show'], $user->id),
            $user->name,
            $user->email
        );

        return redirect()->action([UserController::class, 'index'])
            ->with('status', 'Brukeren ble lagret');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->get('confirm-deleteuser')) {
            $user->delete();

            return redirect()->action('Admin\UserController@index')
                ->with('status', 'Brukeren ble slettet');
        }

        return redirect()->back()
            ->with('status', 'Brukeren ble ikke slettet');
    }
}
