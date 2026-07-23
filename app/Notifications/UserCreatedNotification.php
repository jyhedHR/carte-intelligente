<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserCreatedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $temporaryPassword;

    public function __construct(User $user, string $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur la plateforme GED')
            ->greeting('Bonjour ' . $this->user->prenom . ' ' . $this->user->nom . '!')
            ->line('Votre compte a été créé avec succès.')
            ->line('**Email:** ' . $this->user->email)
            ->line('**Mot de passe temporaire:** ' . $this->temporaryPassword)
            ->action('Se connecter', route('admin.login'))
            ->line('Veuillez changer votre mot de passe après votre première connexion.')
            ->line('Merci d\'utiliser notre plateforme!');
    }
}
