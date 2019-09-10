<?php

namespace LaravelEnso\Impersonate\app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('impersonating')) {
            Auth::onceUsingId(
                $request->session()->get('impersonating')
            );

            return $next($request);
        }

        return $next($request);
    }
}
