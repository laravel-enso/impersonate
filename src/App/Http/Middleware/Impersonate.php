<?php

namespace LaravelEnso\Impersonate\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->hasSession() && $request->session()->has('impersonating')) {
            $this->guard()->onceUsingId(
                $request->session()->get('impersonating')
            );

            return $next($request);
        }

        return $next($request);
    }

    private function guard(): StatefulGuard
    {
        return Auth::guard(Config::get('sanctum.guard', 'web'));
    }
}
