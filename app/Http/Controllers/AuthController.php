<?php

namespace App\Http\Controllers;

use App\Models\Customers;
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
            'mobile_no' => 'required|digits_between:10,15',
        ]);

        $mobile_no = $request->mobile_no;

        if (!str_starts_with($mobile_no, '+')) {
            $mobile_no = '+91' . $mobile_no;  // Add country code if not present
        }

        $otp = rand(100000, 999999);  // Generate OTP

        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $fromNumber = env('TWILIO_PHONE_NUMBER');

        $user = Customers::firstOrCreate([
            'mobile_no' => $mobile_no,
        ]);

        try {
            $twilio = new Client($sid, $authToken);

            $twilio->messages->create(
                $mobile_no,
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
            'mobile_no' => 'required|digits_between:10,15',
            'otp_code' => 'required|digits:6',
        ]);

        $mobile_no = $request->mobile_no;
        if (!str_starts_with($mobile_no, '+')) {
            $mobile_no = '+91' . $mobile_no;
        }

       
        $user = Customers::where('mobile_no', $mobile_no)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Verify OTP and expiry
        if ((string) $user->otp_code !== (string) $request->otp_code || now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Update user as verified
        $user->update([
            'mobile_no_verified_at' => now(),
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

    // update Customers details
    public function update(Request $request, $id)
    {
      
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
        ]);
        $customer = Customers::find($id);
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        $customer->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully',
           'customer' => [
            'mobile_no' => $customer->mobile_no,
            'firstName' => $customer->firstName,
            'lastName' => $customer->lastName,
        ],
        ]);
    }
    public function store(){
       return 'ok';
    }

}
