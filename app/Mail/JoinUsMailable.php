<?php

namespace App\Mail;

use App\Models\JoinUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JoinUsMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $joinUs;

    // Constructor to pass JoinUs data
    public function __construct(JoinUs $joinUs)
    {
        $this->joinUs = $joinUs;
    }

    public function build()
    {
        return $this->view('emails.joinus')  // Specify the email view
                    ->subject('New Join Us Request')  // Email subject
                    ->with([
                        'data' => $this->joinUs
                    ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Join Us Mailable',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.joinus',
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
