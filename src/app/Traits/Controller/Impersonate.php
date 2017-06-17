<?php

namespace LaravelEnso\Impersonate\app\Traits\Controller;

use App\User;

trait Impersonate
{
	public function impersonate(User $user)
    {
        auth()->user()->startImpersonating($user->id);
        flash()->warning(__('Impersonating').' '.$user->full_name);

        return redirect()->back();
    }

    public function stopImpersonating()
    {
        auth()->user()->stopImpersonating();
        flash()->success(__('Welcome Back'));

        return redirect()->back();
    }
}