<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NovoComentarioNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $comentador;
    public $comentario;
    public $article_id;

    public function __construct($titulo, $comentador, $comentario, $article_id)
    {
        $this->titulo = $titulo;
        $this->comentador = $comentador;
        $this->comentario = $comentario;
        $this->article_id = $article_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Novo comentário no seu artigo')
            ->greeting('Olá!')
            ->line('Seu artigo "' . $this->titulo . '" recebeu um novo comentário de ' . $this->comentador . ':')
            ->line('"' . $this->comentario . '"')
            ->action('Visualizar artigo', url('/artigos/' . $this->article_id . '/visualizar'))
            ->line('Obrigado por contribuir com o The English Voice!');
    }
}
