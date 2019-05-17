<?php

namespace LaravelEnso\Impersonate\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Start extends Controller
{
    use AuthorizesRequests;

    public function __invoke(User $user)
    {
        $this->authorize('impersonate', $user);

        session()->put('impersonating', $user->id);

        return [
            'message' => __('Impersonating :user', ['user' => $user->person->name]),
        ];
    }
}
