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
        $request->validate([
            'phone' => 'required|digits_between:10,15',
        ]);

        $phone = $request->phone;

        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . $phone;  // Add country code if not present
        }

        $otp = rand(100000, 999999);  // Generate OTP

        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $fromNumber = env('TWILIO_PHONE_NUMBER');

        $user = MobileUser::firstOrCreate([
            'phone' => $phone,
        ]);

        try {
            $twilio = new Client($sid, $authToken);

            $twilio->messages->create(
                $phone,
                [
                    'from' => $fromNumber,
                    'body' => "Your OTP is: $otp",
                ]
            );

            $otp_expiry_time = \Carbon\Carbon::now('Asia/Kolkata')->addMinutes(30);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => $otp_expiry_time,
            ]);

            return response()->json([
                'message' => 'OTP sent successfully.',
            ]);
        } catch (\Exception $e) {
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

        $phone = $request->phone;
        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . $phone;
        }

        // Retrieve user by phone
        $user = MobileUser::where('phone', $phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Verify OTP and expiry
        if ((string) $user->otp_code !== (string) $request->otp_code || now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Update user as verified
        $user->update([
            'phone_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Generate new JWT token with 15-day expiry
        $jwtSecret = env('JWT_SECRET');
        $payload = [
            'user_id' => $user->id,
            'exp' => time() + (60 * 60 * 24 * 15), // 15 days
        ];

        try {
            $jwt = JWT::encode($payload, $jwtSecret, 'HS256');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error generating token.'], 500);
        }

        // Return response with token
        return response()->json([
            'status' => true,
            'message' => 'User verified successfully.',
            'token' => $jwt,
            'data' => [
                'user' => $user,
            ],
        ]);
    }


}
