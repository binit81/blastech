<?php

namespace Retailcore\Customer_Source;

use Illuminate\Support\ServiceProvider;

class CustomerSourceServiceProvider extends ServiceProvider
{
    Public function boot()
    {
     $this->loadRoutesFrom(__DIR__. '/routes/web.php');   
     $this->loadViewsFrom(__DIR__. '/views', 'customer_source');
	 $this->loadMigrationsFrom(__DIR__. '/database/migrations');
	 }
    public function register()
    {
        
    }

    
    
}