<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiAccessKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
    
        $clientKey = $request->header('ACCESS-KEY');

        if ($clientKey !== env('API_ACCESS_KEY')) {
            return response()->json(['error' => 'Unauthorized. Invalid API Key.'], 401);
        }

        return $next($request);
    
    }
}
