<?php

namespace LaravelEnso\Impersonate\Traits;

trait Impersonates
{
    public function isImpersonating()
    {
        return session()->has('impersonating');
    }
}
