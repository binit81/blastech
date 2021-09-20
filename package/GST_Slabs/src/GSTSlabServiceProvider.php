<?php

namespace Retailcore\GST_Slabs;

use Illuminate\Support\ServiceProvider;

class GSTSlabServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','gst_slab');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');

        $this->publishes([
            // Assets
            __DIR__.'/public/modulejs' => public_path('Retailcore/GST_Slabs/modulejs'),
        ], 'Retailcore/GST_Slabs');



    }
    public function register()
    {

    }



}