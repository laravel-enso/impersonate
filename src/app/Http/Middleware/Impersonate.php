<?php

namespace LaravelEnso\Impersonate\app\Http\Middleware;

use Closure;
use LaravelEnso\PermissionManager\app\Http\Middleware\VerifyRouteAccess;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('impersonating')) {
            auth()->onceUsingId($request->session()->get('impersonating'));

            return (new VerifyRouteAccess())->handle($request, $next);
        }

        return $next($request);
    }
}
