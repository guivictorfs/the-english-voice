<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ArtigoDenunciadoNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $motivo;
    public $article_id;

    public function __construct($titulo, $motivo, $article_id)
    {
        $this->titulo = $titulo;
        $this->motivo = $motivo;
        $this->article_id = $article_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seu artigo foi denunciado')
            ->greeting('Olá!')
            ->line('Seu artigo "' . $this->titulo . '" recebeu uma denúncia.')
            ->line('Motivo da denúncia: ' . $this->motivo)
            ->action('Visualizar artigo', url('/artigos/' . $this->article_id . '/visualizar'))
            ->line('Se você acredita que esta denúncia foi um engano, entre em contato com a equipe.');
    }
}
