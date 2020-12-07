<?php

namespace LaravelEnso\Impersonate\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use LaravelEnso\Core\Models\User;

class Start extends Controller
{
    use AuthorizesRequests;

    public function __invoke(User $user)
    {
        $this->authorize('impersonate', $user);

        Session::put('impersonating', $user->id);

        return ['message' => __('Impersonating :user', ['user' => $user->person->name])];
    }
}
