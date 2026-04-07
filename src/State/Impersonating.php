<?php

namespace LaravelEnso\Impersonate\State;

use Illuminate\Support\Facades\Session;
use LaravelEnso\Core\Contracts\ProvidesState;

class Impersonating implements ProvidesState
{
    public function store(): string
    {
        return 'app';
    }

    public function state(): array
    {
        return ['impersonating' => Session::has('impersonating')];
    }
}
