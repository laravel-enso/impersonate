<?php

namespace LaravelEnso\Impersonate;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Impersonate\app\Http\Middleware\Impersonate;

class ImpersonateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('impersonate', Impersonate::class);
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/impersonate');
    }

    public function register()
    {
        //
    }
}
