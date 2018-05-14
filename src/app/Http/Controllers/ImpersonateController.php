<?php

namespace LaravelEnso\Impersonate\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\Core\app\Models\User;

class ImpersonateController extends Controller
{
    public function start(User $user)
    {
        $this->authorize('impersonate', $user);

        session()->put('impersonating', $user->id);

        return ['message' => __('Impersonating').' '.$user->fullName];
    }

    public function stop()
    {
        session()->forget('impersonating');

        return ['message' => __('Welcome Back')];
    }
}
