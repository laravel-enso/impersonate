<?php

namespace LaravelEnso\Impersonate\app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Core\app\Models\User;

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
