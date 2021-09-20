<?php

namespace Retailcore\BarcodePrinting;

use Illuminate\Support\ServiceProvider;

class BarcodePrintingServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','barcodeprinting');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
    }

}