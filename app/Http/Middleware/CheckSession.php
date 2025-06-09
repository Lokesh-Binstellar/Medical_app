<?php
namespace App\Http\Middleware;

use Closure;
use App\Exceptions\SessionExpiredException;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            throw new SessionExpiredException();
        }

        return $next($request);
    }
}
