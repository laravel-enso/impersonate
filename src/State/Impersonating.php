<?php

namespace LaravelEnso\Impersonate\State;

use Illuminate\Support\Facades\Session;
use LaravelEnso\Core\Contracts\ProvidesState;

class Impersonating implements ProvidesState
{
    public function mutation(): string
    {
        return 'setImpersonating';
    }

    public function state(): mixed
    {
        return Session::has('impersonating');
    }
}
