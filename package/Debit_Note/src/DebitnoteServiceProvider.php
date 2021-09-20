<?php

namespace Retailcore\Debit_Note;

use Illuminate\Support\ServiceProvider;

class DebitnoteServiceProvider extends ServiceProvider
{
    Public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views','debit_note');
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
    }
    public function register()
    {

    }



}