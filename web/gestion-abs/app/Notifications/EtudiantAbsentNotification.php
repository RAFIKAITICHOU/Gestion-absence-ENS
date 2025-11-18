<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CoursSession;

class EtudiantAbsentNotification extends Notification
{
    use Queueable;

    protected $session;

    public function __construct(CoursSession $session)
    {
        $this->session = $session;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('🚨 Absence enregistrée - ' . $this->session->cours->nom)
            ->view('emails.absence', [
                'user' => $notifiable,
                'cours' => $this->session->cours,
                'session' => $this->session
            ]);
    }
}
