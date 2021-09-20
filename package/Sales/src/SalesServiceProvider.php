<?php

namespace Retailcore\Sales;

use Illuminate\Support\ServiceProvider;

class SalesServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views', 'sales');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');



    }
    public function register()
    {

    }



}