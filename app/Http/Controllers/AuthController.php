<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Validation\Rule;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Validator;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|digits_between:10,15',
        ]);

        $mobile_no = $request->mobile_no;

        if (!str_starts_with($mobile_no, '+')) {
            $mobile_no = '+91' . $mobile_no; // Add country code if not present
        }

        $otp = rand(1000, 9999); // Generate OTP

        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $fromNumber = env('TWILIO_PHONE_NUMBER');

        $user = Customers::firstOrCreate([
            'mobile_no' => $mobile_no,
        ]);

        try {
            $twilio = new Client($sid, $authToken);

            $twilio->messages->create($mobile_no, [
                'from' => $fromNumber,
                'body' => "Your OTP is: $otp",
            ]);

            $otp_expiry_time = \Carbon\Carbon::now('Asia/Kolkata')->addMinutes(30);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => $otp_expiry_time,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Failed to send OTP: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function verifyOtp(Request $request)
    {
        // Validate input
        $request->validate([
            'mobile_no' => 'required|digits_between:10,15',
            'otp_code' => 'required|digits:4',
        ]);

        $mobile_no = $request->mobile_no;
        if (!str_starts_with($mobile_no, '+')) {
            $mobile_no = '+91' . $mobile_no;
        }

        $user = Customers::where('mobile_no', $mobile_no)->first();

        if (!$user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User not found.',
                ],
                404,
            );
        }

        // Verify OTP and expiry
        if ((string) $user->otp_code !== (string) $request->otp_code || now()->gt($user->otp_expires_at)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Invalid or expired OTP.',
                ],
                400,
            );
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
            // 'exp' => time() + 60 * 60 * 24 * 15, // 15 days
            'exp' => time() + (60 * 60 * 24 * 365 * 100), // 100 years
        ];

        try {
            $jwt = JWT::encode($payload, $jwtSecret, 'HS256');
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Error generating token.',
                ],
                500,
            );
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
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'lastName' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($request->user_id)],
            ],
            [
                'firstName.required' => 'First name is required.',
                'firstName.regex' => 'First name must contain only letters.',
                'lastName.required' => 'Last name is required.',
                'lastName.regex' => 'Last name must contain only letters.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already taken.',
            ],
        );

        if ($validator->fails()) {
            $firstMessage = $validator->errors()->first();
            return response()->json(
                [
                    'status' => false,
                    'message' => $firstMessage,
                ],
                422,
            );
        }

        $userId = $request->get('user_id');
        $customer = Customers::find($userId);

        if (!$customer) {
            return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
        }

        $customer->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully',
            'data' => [
                'mobile_no' => $customer->mobile_no,
                'firstName' => $customer->firstName,
                'lastName' => $customer->lastName,
                'email' => $customer->email,
            ],
        ]);
    }

    public function getCustomerDetails(Request $request)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'user_id is required',
                ],
                400,
            );
        }

        $customer = Customers::find($userId);

        if (!$customer) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Customer not found',
                ],
                404,
            );
        }

        return response()->json([
            'status' => true,
            'data' => [
                'firstName' => $customer->firstName,
                'lastName' => $customer->lastName,
                'email' => $customer->email,
                'mobile_no' => $customer->mobile_no,
            ],
        ]);
    }

    public function store()
    {
        return 'ok';
    }
}
