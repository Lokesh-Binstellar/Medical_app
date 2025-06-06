<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SendMessageEvent; // ✅ Import your new event

class PusherController extends Controller
{
    public function trigger(Request $request)
    {
        // ✅ Assume receiver_id is coming from request
         $user = auth()->user(); 
        $receiverId = $request->input('receiver_id'); // e.g., 5
        $message = 'Private event triggered by Lokesh using SendMessageEvent!';

        // ✅ Fire event to specific user
        event(new SendMessageEvent($message, $user->id));

        return response()->json([
            'status' => 'SendMessageEvent sent to user.' . $user->id
        ]);
    }
}
