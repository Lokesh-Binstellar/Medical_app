<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthTokenController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    // public function login(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     $token = $user->createToken('api_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login successful.',
    //         'token' => $token,
    //         'user' => $user
    //     ]);
    // }

    // public function login(Request $request)
    // {
    //     // Validate the input data
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:6',
    //     ]);

    //     // Find user by email
    //     $user = User::where('email', $request->email)->first();

    //     // Check if user exists and password is correct
    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // Generate a new API token for the user
    //     $token = $user->createToken('api_token')->plainTextToken;

    //     // Return the response with the token and user details
    //     return response()->json([
    //         'message' => 'Login successful.',
    //         'token' => $token,
    //         'user' => $user
    //     ]);
    // }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if user already has a token
        if ($user->api_token) {
            $token = $user->api_token;
        } else {
            // Generate a new token and save it
            $token = $user->createToken('api_token')->plainTextToken;
            $user->api_token = $token;
            $user->save();
        }

        // Prepare user data without 'api_token'
        $userData = $user->only(['id', 'role_id', 'name', 'email']);

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $userData
        ]);
        
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function profile(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
