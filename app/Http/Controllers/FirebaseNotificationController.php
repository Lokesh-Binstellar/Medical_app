<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\FirebasePushNotification;
use Illuminate\Support\Facades\Notification;

class FirebaseNotificationController extends Controller
{
 public function sendNotification()
{
    $user = User::find(1);  // or the user you want to send to

    Notification::send($user, new FirebasePushNotification(
        'Hello from Laravel',
        'This is a test notification using Firebase Cloud Messaging'
    ));

    return 'Notification Sent!';
}
}
