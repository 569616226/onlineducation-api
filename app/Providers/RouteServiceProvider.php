<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
        Route::pattern( 'role', '[0-9]+' );
        Route::pattern( 'permission', '[0-9]+' );
        Route::pattern( 'educational', '[0-9]+' );
        Route::pattern( 'user', '[0-9]+' );
        Route::pattern( 'guest', '[0-9]+' );
        Route::pattern( 'video', '[0-9]+' );
        Route::pattern( 'nav', '[0-9]+' );
        Route::pattern( 'lesson', '[0-9]+' );
        Route::pattern( 'section', '[0-9]+' );
        Route::pattern( 'label', '[0-9]+' );
        Route::pattern( 'discusse', '[0-9]+' );
        Route::pattern( 'setting', '[0-9]+' );
        Route::pattern( 'message', '[0-9]+' );
        Route::pattern( 'teacher', '[0-9]+' );
        Route::pattern( 'advert', '[0-9]+' );
        Route::pattern( 'vip', '[0-9]+' );
        Route::pattern( 'order', '[0-9]+' );
        Route::pattern( 'genre', '[0-9]+' );
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
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
        Route::middleware( 'web' )
            ->namespace( $this->namespace )
            ->group( base_path( 'routes/web.php' ) );
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
        Route::prefix( 'api' )
            ->middleware( 'api' )
            ->namespace( $this->namespace )
            ->group( base_path( 'routes/api.php' ) );
    }
}
