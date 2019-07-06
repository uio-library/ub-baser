<?php

namespace App\Providers;

use App\Http\Controllers\PageController;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
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
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(Router $router)
    {
        try {
            $this->mapPages($router);
        } catch (PDOException $e) {
            // Database offline or not created/migrated yet. Ignore.
        }

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
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
     * Add editable static pages like `litteraturkritikk.intro.edit`
     *
     * @param Router $router
     */
    protected function mapPages(Router $router)
    {
        foreach (Page::all() as $page) {
            $this->mapPage($router, $page);
        }
    }

    /**
     * Dynamically create routes from the pages table.
     *
     * @param Router $router
     */
    public function mapPage(Router $router, Page $page)
    {
        $router->get($page->route, ['middleware' => ['web', 'auth', 'secure.content'], 'as' => $page->name, function () use ($page) {
            $c = new PageController();

            return $c->show($page);
        }]);

        $router->get($page->route . '/edit', ['middleware' => ['web', 'auth', 'secure.content'], 'as' => $page->name . '.edit', function () use ($page) {
            $c = new PageController();

            return $c->edit($page);
        }]);

        $router->post($page->route . '/update', ['middleware' => ['web', 'auth'], 'as' => $page->name . '.update', function (Request $request) use ($page) {
            $c = new PageController();

            return $c->update($request, $page);
        }]);
    }
}
