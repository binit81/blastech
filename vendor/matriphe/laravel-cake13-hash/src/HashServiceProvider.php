<?php

namespace Matriphe\Cake13Hash;

use Illuminate\Support\ServiceProvider;

class HashServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cake.php' => config_path('cake.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/cake.php', 'cake'
        );
    }

    /**
     * Register the application services.
     *
     */
    public function register()
    {
        $this->app->singleton('hash', function () {
            return new Cake13Hash(config('cake.salt'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash'];
    }
}
