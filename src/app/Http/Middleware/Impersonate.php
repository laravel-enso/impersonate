<?php

namespace LaravelEnso\Impersonate\app\Http\Middleware;

use Closure;
use LaravelEnso\Core\app\Http\Middleware\VerifyRouteAccess;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('impersonating')) {
            auth()->onceUsingId($request->session()->get('impersonating'));

            if (class_exists(VerifyRouteAccess::class)) {
                return (new VerifyRouteAccess())->handle($request, $next);
            }
        }

        return $next($request);
    }
}
