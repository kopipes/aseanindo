<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationOtp extends Mailable
{
    use Queueable, SerializesModels;
    private $otp;
    private $subjectEmail;
    private $action;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $type)
    {
        $this->otp = $otp;
        switch ($type) {
            case 'forgot-password':
                $this->subjectEmail = 'Forgot Password';
                $this->action = 'complete your forgot password';
                break;
            case 'edit-profile':
                $this->subjectEmail = 'Edit Profile';
                $this->action = 'complete your edit profile';
                break;
            default:
                break;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectEmail,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.verification-otp',
            with: [
                'otp' => $this->otp,
                'action' => $this->action
            ],
        );
    }

}