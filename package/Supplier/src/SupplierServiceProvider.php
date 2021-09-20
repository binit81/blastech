<?php

namespace Retailcore\Supplier;

use Illuminate\Support\ServiceProvider;

class SupplierServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','supplier');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');



    }


}