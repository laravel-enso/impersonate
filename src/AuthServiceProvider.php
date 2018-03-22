<?php

namespace LaravelEnso\Impersonate;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Impersonate\app\Policies\ImpersonatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->policies = [
            User::class => ImpersonatePolicy::class,
        ];

        $this->registerPolicies();
    }
}
