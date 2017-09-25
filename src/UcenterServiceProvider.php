<?php namespace Binaryoung\Ucenter;

use Illuminate\Support\ServiceProvider;

class UcenterServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (!str_contains($this->app->version(), 'Lumen')) {
            $this->publishes([
                __DIR__.'/config/ucenter.php' => config_path('ucenter.php'),
            ]);
        }
        
        $this->mergeConfigFrom(__DIR__.'/config/ucenter.php', 'ucenter');

        $this->app->bind('ucenter', function ($app) {
            return new Ucenter;
        });

        $this->app->bind('Binaryoung\Ucenter\Contracts\Api', config('ucenter.service'));

        if (str_contains($this->app->version(), 'Lumen') && ! property_exists($this->app, 'router')) {
            $router = $this->app;
        } else {
            $router = $this->app->router;
        }

        $router->any(config('ucenter.url').'/api/'.config('ucenter.apifilename'), '\Binaryoung\Ucenter\Controllers\ApiController@run');
    }
}
