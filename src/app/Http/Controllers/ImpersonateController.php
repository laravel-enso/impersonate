<?php

namespace LaravelEnso\Impersonate\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ImpersonateController extends Controller
{
    use AuthorizesRequests;

    public function start(User $user)
    {
        $this->authorize('impersonate', $user);

        session()->put('impersonating', $user->id);

        return [
            'message' => __('Impersonating').' '.$user->person->name,
        ];
    }

    public function stop()
    {
        session()->forget('impersonating');

        return [
            'message' => __('Welcome Back'),
        ];
    }
}
