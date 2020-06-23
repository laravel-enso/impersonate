<?php

namespace LaravelEnso\Impersonate\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->hasSession() && $request->session()->has('impersonating')) {
            Auth::onceUsingId(
                $request->session()->get('impersonating')
            );

            return $next($request);
        }

        return $next($request);
    }
}
