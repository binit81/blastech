<?php

namespace Retailcore\PO;

use Illuminate\Support\ServiceProvider;

class POServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','PO');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');

    }
    public function register()
    {

    }



}