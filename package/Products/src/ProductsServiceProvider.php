<?php

namespace Retailcore\Products;

use Illuminate\Support\ServiceProvider;

class ProductsServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','products');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
    }

}