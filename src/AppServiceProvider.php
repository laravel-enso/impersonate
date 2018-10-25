<?php

namespace LaravelEnso\Impersonate;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Impersonate\app\Http\Middleware\Impersonate;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->middleware(
            'impersonate', Impersonate::class
        );

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

    public function register()
    {
        //
    }
}
