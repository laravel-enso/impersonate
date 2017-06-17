<?php

namespace LaravelEnso\Impersonate\app\Traits\Model;

trait Impersonate
{
	public function startImpersonating(int $id)
    {
        session()->put('impersonating', $id);
    }

    public function stopImpersonating()
    {
        session()->forget('impersonating');
    }

    public function isImpersonating()
    {
        return session()->has('impersonating');
    }
}