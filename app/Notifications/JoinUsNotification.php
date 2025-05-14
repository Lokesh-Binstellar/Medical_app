<?php

namespace App\Notifications;

use App\Models\JoinUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JoinUsNotification extends Notification
{
    use Queueable;

    protected $joinUs;

    /**
     * Create a new notification instance.
     *
     * @param JoinUs $joinUs
     */
    public function __construct(JoinUs $joinUs)
    {
        $this->joinUs = $joinUs;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Pass data to the view
        return (new MailMessage)
                    ->subject('New Join Us Request')
                    ->view('emails.joinus', ['data' => $this->joinUs]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Optional array representation for notifications
        ];
    }
}
