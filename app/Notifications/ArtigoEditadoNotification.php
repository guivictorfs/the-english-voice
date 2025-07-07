<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ArtigoEditadoNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $editor;
    public $article_id;

    public function __construct($titulo, $editor, $article_id)
    {
        $this->titulo = $titulo;
        $this->editor = $editor;
        $this->article_id = $article_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seu artigo foi editado')
            ->greeting('Olá!')
            ->line('Seu artigo "' . $this->titulo . '" foi editado por ' . $this->editor . '.')
            ->action('Visualizar artigo', url('/artigos/' . $this->article_id . '/visualizar'))
            ->line('Se não reconhece esta alteração, entre em contato com a equipe.');
    }
}
