<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SendMessageEvent; // ✅ Import your new event

class PusherController extends Controller
{
    public function trigger()
    {
        $message = ' Event triggered by lokesh using SendMessageEvent!';

        // ✅ Fire the new event to global-channel
        event(new SendMessageEvent($message));

        return response()->json(['status' => 'SendMessageEvent sent']);
    }
}
