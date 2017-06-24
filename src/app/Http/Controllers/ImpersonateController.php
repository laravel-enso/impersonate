<?php

namespace LaravelEnso\Impersonate\app\Http\Controllers;

use LaravelEnso\Core\app\Models\User;

class ImpersonateController
{
    public function start(User $user)
    {
        session()->put('impersonating', $user->id);
        flash()->warning(__('Impersonating').' '.$user->full_name);

        return redirect()->back();
    }

    public function stop()
    {
        session()->forget('impersonating');
        flash()->success(__('Welcome Back'));

        return redirect()->back();
    }
}
