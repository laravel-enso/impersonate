<?php

namespace LaravelEnso\Impersonate\app\Traits;

trait Impersonates
{
    public function isImpersonating()
    {
        return session()->has('impersonating');
    }
}
