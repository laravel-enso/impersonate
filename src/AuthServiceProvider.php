<?php

namespace LaravelEnso\Impersonate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Gate::define('impersonate', function ($user, $targetUser) {
            return $user->can('access-route', 'core.impersonate.start')
                && $user->id !== $targetUser->id
                && !$user->isImpersonating();
        });
    }
}
