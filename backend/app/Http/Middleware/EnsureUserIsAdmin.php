<?php

declare(strict_types=1);

namespace App\Http\Middleware;

class EnsureUserIsAdmin
{
    public function handle($request, \Closure $next)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
