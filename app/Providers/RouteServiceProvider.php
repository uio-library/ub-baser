<?php

namespace App\Providers;

use App\Http\Controllers\PageController;
use App\Page;
use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(DB $db, Router $router)
    {
        foreach (Page::all() as $page) {
            $router->get($page->route, ['as' => $page->name, function () use ($page) {
                $c = new PageController();

                return $c->show($page);
            }]);

            $router->get($page->route . '/edit', ['as' => $page->name . '.edit', function () use ($page) {
                $c = new PageController();

                return $c->edit($page);
            }]);

            $router->post($page->route . '/update', ['as' => $page->name . '.update', function (Request $request) use ($page) {
                $c = new PageController();

                return $c->update($request, $page);
            }]);
        }

        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
