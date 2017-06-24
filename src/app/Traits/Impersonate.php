<?php

namespace LaravelEnso\Impersonate\app\Traits;

trait Impersonate
{
    public function isImpersonating()
    {
        return session()->has('impersonating');
    }
}
