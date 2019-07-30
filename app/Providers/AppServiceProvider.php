<?php

namespace App\Providers;

use App\Dommer\DommerSchema;
use App\Letras\LetrasSchema;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        LitteraturkritikkSchema::class => LitteraturkritikkSchema::class,
        LetrasSchema::class => LetrasSchema::class,
        DommerSchema::class => DommerSchema::class,
    ];
}
