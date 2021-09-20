<?php

namespace Retailcore\Inward_Stock;

use Illuminate\Support\ServiceProvider;

class InwardStockServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','inward_stock');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
    }
    public function register()
    {

    }



}