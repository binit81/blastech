<?php

namespace Retailcore\DamageProducts;

use Illuminate\Support\ServiceProvider;

class DamageProductsServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','damageproducts');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
    }

}