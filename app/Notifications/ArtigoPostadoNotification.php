<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArtigoPostadoNotification extends Notification
{
    use Queueable;

    public $titulo;

    public function __construct($titulo)
    {
        $this->titulo = $titulo;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Novo artigo postado')
                    ->greeting('Olá!')
                    ->line('Um novo artigo foi postado: "' . $this->titulo . '"')
                    ->action('Ver artigos', url('/artigos'))
                    ->line('Obrigado por contribuir com o The English Voice!');
    }
}
