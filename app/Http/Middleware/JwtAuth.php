<?php

namespace App\Http\Middleware;

use App\Models\MobileUser;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            return response()->json(['message' => 'Authorization token not provided.'], 401);
        }
        $token = str_replace('Bearer ', '', $authHeader);


        try {
            // Decode the token using JWT_KEY from config
            $jwtSecret = Config::get('app.jwt_key');
            $decoded = JWT::decode($token, $jwtSecret, ['HS256']);

            // Get the user ID from the decoded token
            $userId = $decoded->user_id;

            // Fetch user from database using the ID from the token
            $user = MobileUser::find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            // Attach the user to the request for further use in the controller
            $request->attributes->add(['user' => $user]);

        }catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }
        return $next($request);

        
    }
}
