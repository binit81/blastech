<?php

namespace Retailcore\Customer;

use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    Public function boot()
    {
     $this->loadRoutesFrom(__DIR__. '/routes/web.php');   
     $this->loadViewsFrom(__DIR__. '/views', 'customer');
	 $this->loadMigrationsFrom(__DIR__. '/database/migrations');
	 //$this->publicResources(__DIR__. '/modulejs/customer');



	 }
    public function register()
    {
        
    }

    
    
}