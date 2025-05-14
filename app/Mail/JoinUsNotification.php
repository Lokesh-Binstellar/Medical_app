<?php

namespace App\Mail;

use App\Models\JoinUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JoinUsNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $joinUs;

    public function __construct(JoinUs $joinUs)
    {
        $this->joinUs = $joinUs;
    }
     public function build()
    {
        return $this->view('emails.joinus')  // View for the email
                    ->subject('New Join Us Request')
                    ->with([
                        'type' => $this->joinUs->type,
                        'first_name' => $this->joinUs->first_name,
                        'last_name' => $this->joinUs->last_name,
                        'email' => $this->joinUs->email,
                        'phone_number' => $this->joinUs->phone_number,
                        'message' => $this->joinUs->message,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Join Us Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
