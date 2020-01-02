<?php

namespace LaravelEnso\Impersonate\App\Traits;

trait Impersonates
{
    public function isImpersonating()
    {
        return session()->has('impersonating');
    }
}
