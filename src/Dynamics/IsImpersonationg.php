<?php

namespace LaravelEnso\Impersonate\Dynamics;

use Closure;
use Illuminate\Support\Facades\Session;
use LaravelEnso\DynamicMethods\Contracts\Method;
use LaravelEnso\Users\Models\User;

class IsImpersonationg implements Method
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'isImpersonating';
    }

    public function closure(): Closure
    {
        return fn () => Session::has('impersonating');
    }
}
