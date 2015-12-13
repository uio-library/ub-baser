<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
     * List of all user rights.
     *
     * @var array
     */
    public static $rights = [
        'admin',
        'litteraturkritikk',
        'letras',
        'opes',
        'dommer',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        foreach (self::$rights as $right) {
            $gate->define($right, function ($user) use ($right) {
                return in_array($right, $user->rights);
            });
        }
    }
}
