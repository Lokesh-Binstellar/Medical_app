<?php

namespace App\Http\Middleware;

use App\Models\Customers;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\Key;

class AuthTest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        $token = $authHeader; // normally from Authorization header
        $jwtSecret = env('JWT_SECRET');
        if ($token == null) {

            return response()->json([
                'status' => false,
                'message' => 'jwt token required.'
            ], 401);
        }

        try {
            $jwtSecret = env('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));

            $customerId = $decoded->user_id ?? null;

            if (!$customerId) {
                return response()->json(['message' => 'Invalid token structure.'], 401);
            }

            $customer = Customers::find($customerId);

            if (!$customer) {
                return response()->json(['message' => 'Customer not found.'], 404);
            }

            // 👇 ID ko request ke attributes me set karo
            $request->attributes->add(['user_id' => $customerId]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid or expired token.',
                'error' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
