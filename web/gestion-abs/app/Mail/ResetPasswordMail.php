<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword;

    /**
     * Crée une nouvelle instance avec le mot de passe généré.
     */
    public function __construct($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * Enveloppe de l’email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre nouveau mot de passe'
        );
    }

    /**
     * Contenu de l’e-mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'password' => $this->newPassword
            ]
        );
    }

    /**
     * Pas de pièces jointes.
     */
    public function attachments(): array
    {
        return [];
    }
}
