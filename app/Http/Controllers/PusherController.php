<?php
namespace App\Http\Controllers;

use App\Models\Customers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Broadcast;
use Illuminate\Http\Request;
use App\Events\SendMessageEvent; 
use Pusher\Pusher;// ✅ Import your new event

class PusherController extends Controller
{
    public function trigger(Request $request)
    {
        $message = 'Event triggered by lokesh using SendMessageEvent!';
        $receiverId = $request->query('receiver_id');

        if (!$receiverId) {
            return response()->json(['status' => false, 'error' => 'Receiver ID required'], 400);
        }

        event(new SendMessageEvent($message, $receiverId));

        return response()->json(['status' => 'SendMessageEvent sent']);
    }

    public function triggerCall(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // 🧠 Optional: find user if you need token
        $user = Customers::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // 👇 Use your Pusher identifier field (e.g., token, or phone, or id)
        $channelToken = $user->id; // or or $user->phone etc.

        $message = 'You have an incoming call!';
        event(new SendMessageEvent($message, $channelToken));

        return response()->json([
            'status' => 'Call event sent to user with token: ' . $channelToken,
        ]);
    }

    // public function authenticate(Request $request)
    // {
    //     $token = $request->header('Authorization');
    //     if (!$token) {
    //         return response()->json(['error' => 'Token is required'], 401);
    //     }

    //     try {
    //         $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
    //         $userId = $decoded->user_id ?? null;

    //         if (!$userId) {
    //             return response()->json(['error' => 'Invalid token structure'], 401);
    //         }

    //         $user = Customers::find($userId);
    //         if (!$user) {
    //             return response()->json(['error' => 'User not found'], 404);
    //         }

    //         // 👇 Set user manually for Broadcast
    //         $request->setUserResolver(function () use ($user) {
    //             return $user;
    //         });
 
    //        $authResponse= Broadcast::routes(['middleware' => ['authTest']]); 


    //          return response()->json([
    //         'message' => 'Broadcast authentication successful.',
    //         'auth_data' => json_decode($authResponse->getContent(), true), // decode response content
    //     ]);

    //     } catch (\Exception $e) {
    //         return response()->json(
    //             [
    //                 'error' => 'Invalid or expired token',
    //                 'details' => $e->getMessage(),
    //             ],
    //             401,
    //         );
    //     }
    // }



    public function authenticate(Request $request)
{
    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['error' => 'Token is required'], 401);
    }

    try {
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        $userId = $decoded->user_id ?? null;

        if (!$userId) {
            return response()->json(['error' => 'Invalid token structure'], 401);
        }

        $user = Customers::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // 👇 Manually generate Pusher auth response (instead of Broadcast::auth)
        $socketId = $request->input('socket_id');
        $channelName = $request->input('channel_name');

        if (!$socketId || !$channelName) {
            return response()->json(['error' => 'Socket ID and Channel Name are required'], 400);
        }

        // 🔥 Generate Pusher auth signature (mimics Broadcast::auth)
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $authResponse = $pusher->socket_auth($channelName, $socketId);

        return response()->json(json_decode($authResponse));
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Invalid or expired token',
            'details' => $e->getMessage()
        ], 401);
    }
}
}
