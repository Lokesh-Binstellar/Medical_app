<?php

namespace App\Http\Controllers;

use App\Models\MobileUser;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Firebase\JWT\JWT;
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

            // Return success response
            return response()->json([
                'message' => 'OTP sent successfully.',
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
    $request->validate([
        'phone' => 'required|digits_between:10,15',
        'otp_code' => 'required|digits:6',
    ]);

    $phone = $request->phone;

    // Add +91 if not present
    if (!str_starts_with($phone, '+')) {
        $phone = '+91' . $phone;
    }
    $user = MobileUser::where('phone', $phone)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }


    Log::info("OTP Received: " . $request->otp_code);
    Log::info("OTP Stored in DB: " . $user->otp_code);
    Log::info("OTP Expiry Time: " . $user->otp_expires_at);

    if ((string) $user->otp_code !== (string) $request->otp_code || now()->gt($user->otp_expires_at)) {
        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }

    $user->update([
        'phone_verified_at' => now(),
        'otp_code' => null,
        'otp_expires_at' => null,
    ]);

    $jwtSecret = env('JWT_SECRET');
    $payload = [
        'user_id' => $user->id,
        'exp' => time() + 3600,
    ];

    try {
        $jwt = JWT::encode($payload, $jwtSecret, 'HS256');
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error generating JWT token.'], 500);
    }

    return response()->json([
        'message' => 'User verified successfully.',
        'token' => $jwt,
        'user' => $user,
    ]);
}
}
