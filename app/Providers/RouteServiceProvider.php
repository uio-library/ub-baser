<?php

namespace App\Providers;

use App\Base;
use App\Page;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use PDOException;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        // De fleste basene har numeriske ID-er, men f.eks. Bibsys-basen har alfanumeriske
        // Vi bør ikke være altfor strenge her.
        Route::pattern('id', '[a-z-0-9]+');

        // Denne brukes for omdirigeringer
        Route::pattern('numeric', '[0-9]+');

        Route::pattern('page', '[a-z-]+/[0-9a-z-]+');

        Route::pattern('base', '[0-9a-z-]+');

        Route::bind('base', function ($value) {
            return Base::find($value);
        });

        Route::bind('page', function ($value) {
            $page = Page::firstOrNew([
                'slug' => $value,
                'lang' => \App::getLocale(),
            ]);
            if (!$page->exists) {
                // Check if the page slug contains a valid basepath
                $basepath = explode('/', $value)[0];
                $base = Base::where('basepath', '=', $basepath)->first();
                if (is_null($base)) {
                    abort(404, 'Base not found');
                }

                // Bootstrap a new page
                $page->permission = $base->id;
                $page->base_id = $base->id;
                $page->lang = \App::getLocale();
                $page->layout = $base->id . '.layout';
            }
            return $page;
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapDynamicRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the dynamic routes for the application.
     *
     * @return void
     */
    protected function mapDynamicRoutes()
    {
        try {
            Base::get()->each(function (Base $base) {
                Route::prefix($base->basepath)
                    ->namespace($base->fqn())
                    ->group(function () use ($base) {

                        // Routes that do not need session
                        Route::get('/autocomplete', 'Controller@autocomplete');
                        Route::get('/data', 'Controller@data');

                        Route::middleware('web')
                            ->group(function () use ($base) {

                                // Standard routes for this base
                                Route::get('/', 'Controller@index');
                                Route::get('/{numeric}', 'Controller@redirectToRecord');
                                Route::get('/record/', 'Controller@redirectToHome');
                                Route::get('/record/{id}', 'Controller@show');

                                // Authorized routes for this base
                                Route::middleware('can:' . $base->id)
                                    ->group(function () {
                                        Route::get('/record/_new', 'Controller@create');
                                        Route::post('/record', 'Controller@store');
                                        Route::get('/record/{id}/edit', 'Controller@edit');
                                        Route::put('/record/{id}', 'Controller@update');

                                        Route::delete('/record/{id}', 'Controller@destroy');
                                        Route::post('/record/{id}/restore', 'Controller@restore');
                                    });

                                // Additional routes for this base
                                @include base_path('routes/bases/' . $base->id . '.php');
                            });
                    });
            });
        } catch (PDOException $ex) {
            // During site setup / DB migration
        }

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(function () {
                Route::middleware('login')
                    ->group(function () {
                        Route::get('{page}/edit', 'PageController@edit');
                        Route::post('{page}/update', 'PageController@update');
                        Route::post('upload-image', 'PageController@uploadImage');
                    });
                Route::get('{page}', 'PageController@show');
            });
    }
}
