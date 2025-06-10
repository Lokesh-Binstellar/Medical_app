<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\Message;


class FirebasePushNotification extends Notification
{
    protected $title;
    protected $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    // Specify that this notification will be sent via FCM
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    // Build the FCM message
    public function toFcm($notifiable)
    {
        return Message::create()
            ->setData(['key' => 'value'])  // optional data payload
            ->setNotification([
                'title' => $this->title,
                'body' => $this->body,
                'sound' => 'default',
            ]);
    }
}
