<?php

namespace App\Http\Controllers;

use App\Models\MobileUser;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{


    public function sendOtp(Request $request)
{
    // Step 1: Validate phone number
    $request->validate([
        'phone' => 'required|digits_between:10,15',
    ]);

    $phone = $request->phone;

    // Ensure +91 is prefixed
    if (!str_starts_with($phone, '+')) {
        $phone = '+91' . $phone;  // Add country code if not present
    }

    // Step 2: Generate OTP (6 digits)
    $otp = rand(100000, 999999);  // Generate OTP

    // Step 3: Twilio credentials from .env
    $sid = env('TWILIO_SID');
    $authToken = env('TWILIO_AUTH_TOKEN');
    $fromNumber = env('TWILIO_PHONE_NUMBER');

    // Step 4: Check if user exists, else create
    $user = MobileUser::firstOrCreate([
        'phone' => $phone,
    ]);

    try {
        // Step 5: Send OTP using Twilio
        $twilio = new Client($sid, $authToken);

        $twilio->messages->create(
            $phone,
            [
                'from' => $fromNumber,
                'body' => "Your OTP is: $otp",  // OTP message
            ]
        );

        // Set OTP expiry time with correct time zone (Asia/Kolkata)
        $otp_expiry_time = \Carbon\Carbon::now('Asia/Kolkata')->addMinutes(30);

        // Step 6: Save OTP and expiry time to the database
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => $otp_expiry_time,
        ]);

        // Generate JWT token after OTP sent
        $jwtSecret = env('JWT_SECRET');
        $payload = [
            'user_id' => $user->id,
            'exp' => time() + 600, // token 10 min ke liye valid hai
        ];

        try {
            $jwt = JWT::encode($payload, $jwtSecret, 'HS256');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error generating JWT token.'], 500);
        }

        // Return success response with JWT token
        return response()->json([
            'message' => 'OTP sent successfully.',
            'token' => $jwt,
        ]);
    } catch (\Exception $e) {
        // Handle errors and return failure response
        return response()->json([
            'message' => 'Failed to send OTP: ' . $e->getMessage(),
        ], 500);
    }
}




public function verifyOtp(Request $request)
{
    // Validate input
    $request->validate([
        'phone' => 'required|digits_between:10,15',
        'otp_code' => 'required|digits:6',
    ]);

    // Extract token from Authorization header
    $authHeader = $request->header('Authorization');
    if (!$authHeader) {
        return response()->json(['message' => 'Authorization token not provided.'], 401);
    }
    $token = str_replace('Bearer ', '', $authHeader);

    try {
        // Decode the JWT token
        $jwtSecret = env('JWT_SECRET');
        $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));
        $userIdFromToken = $decoded->user_id;
    } catch (\Exception $e) {
        return response()->json(['message' => 'Invalid token.'], 401);
    }

    // Validate the phone number and retrieve the user
    $phone = $request->phone;
    if (!str_starts_with($phone, '+')) {
        $phone = '+91' . $phone;
    }

    // Fetch user from database using user ID from token
    $user = MobileUser::where('id', $userIdFromToken)
                      ->where('phone', $phone)
                      ->first();

    if (!$user) {
        return response()->json(['message' => 'User not found or token mismatch.'], 404);
    }

    // Verify OTP
    if ((string) $user->otp_code !== (string) $request->otp_code || now()->gt($user->otp_expires_at)) {
        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }

    // Update user as verified
    $user->update([
        'phone_verified_at' => now(),
        'otp_code' => null,
        'otp_expires_at' => null,
    ]);

    // Return success response
    return response()->json([
        'status' => true,
        'message' => 'User verified successfully.',
        'data' => [
            'user' => $user
        ]
    ]);
}

}
