<?php

namespace LaravelEnso\Impersonate;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Core\Models\User;
use LaravelEnso\DynamicMethods\Services\Methods;
use LaravelEnso\Impersonate\DynamicMethods\IsImpersonationg;
use LaravelEnso\Impersonate\Http\Middleware\Impersonate;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('impersonate', Impersonate::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        Methods::bind(User::class, [IsImpersonationg::class]);
    }
}
