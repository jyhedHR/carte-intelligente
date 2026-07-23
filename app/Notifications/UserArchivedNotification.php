<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserArchivedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $reason;

    public function __construct(User $user, string $reason = null)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Compte archivé - Plateforme GED')
            ->greeting('Bonjour ' . $this->user->prenom . ' ' . $this->user->nom)
            ->line('Votre compte a été archivé.')
            ->line('Vous n\'avez plus accès à la plateforme.');

        if ($this->reason) {
            $mail->line('**Raison:** ' . $this->reason);
        }

        $mail->line('Si vous pensez qu\'il s\'agit d\'une erreur, veuillez contacter l\'administrateur.');

        return $mail;
    }
}
