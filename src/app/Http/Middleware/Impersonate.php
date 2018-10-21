<?php

namespace LaravelEnso\Impersonate\app\Http\Middleware;

use Closure;

class Impersonate
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('impersonating')) {
            auth()->onceUsingId(
                $request->session()->get('impersonating')
            );

            return $next($request);
        }

        return $next($request);
    }
}
