<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenValidation
{


public function handle(Request $request, Closure $next)
{

    // Check if the request has a Bearer token
    $token = $request->bearerToken();
    
    if (!$token) {
        return response()->json(['message' => 'Unauthorized. No token provided.'], 401);
    }

    // If a token exists, proceed to check if it's valid
    $user = Auth::guard('sanctum')->user(); // Auth::guard('sanctum') ensures the token is validated
    
    if (!$user) {
        return response()->json(['message' => 'Unauthorized. Invalid token.'], 401);
    }

    // Proceed with the request if token is valid
    return $next($request);
}

}
