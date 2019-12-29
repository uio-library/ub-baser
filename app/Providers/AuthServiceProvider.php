<?php

namespace App\Providers;

use App\Base;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use PDOException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // User::class => UserPolicy::class,
    ];

    /**
     * List of gates
     *
     * @var array
     */
    public static $gateLabels = [
    ];

    public static function listGates()
    {
        $gates = \Gate::abilities();
        return array_keys($gates);
    }

    /**
     * Register any application authentication / authorization services.
     *
     * @param GateContract $gate
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        // Define the "admin" gate
        $gate->define('admin', function (User $user) {
            return in_array('admin', $user->rights);
        });
        self::$gateLabels['admin'] = trans('rights.admin');

        // Define a gate for each base
        try {
            foreach (Base::get() as $base) {
                $gate->define($base->id, function (User $user) use ($base) {
                    return in_array($base->id, $user->rights);
                });
                self::$gateLabels[$base->id] = trans('rights.can-edit', ['name' => $base->title]);
            }
        } catch (PDOException $ex) {
            // During site setup / DB migration
        }
    }
}
