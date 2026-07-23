<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RoleAssignedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $role;

    public function __construct(User $user, string $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau rôle attribué - Plateforme GED')
            ->greeting('Bonjour ' . $this->user->prenom . ' ' . $this->user->nom)
            ->line('Un nouveau rôle vous a été attribué sur la plateforme.')
            ->line('**Rôle:** ' . $this->role)
            ->line('Vous pouvez maintenant accéder aux fonctionnalités associées à ce rôle.')
            ->action('Accéder à la plateforme', url('/'))
            ->line('Merci!');
    }
}
