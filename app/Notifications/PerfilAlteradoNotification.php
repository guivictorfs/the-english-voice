<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PerfilAlteradoNotification extends Notification
{
    use Queueable;

    public $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $msg = 'Os seguintes dados do seu perfil foram alterados: ' . implode(', ', $this->fields) . '.';
        return (new MailMessage)
            ->subject('Seu perfil foi atualizado')
            ->greeting('Olá!')
            ->line($msg)
            ->line('Se você não reconhece essas alterações, entre em contato imediatamente.');
    }
}
