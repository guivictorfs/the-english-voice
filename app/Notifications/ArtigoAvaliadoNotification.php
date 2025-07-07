<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ArtigoAvaliadoNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $nota;
    public $avaliador;
    public $article_id;

    public function __construct($titulo, $nota, $avaliador, $article_id)
    {
        $this->titulo = $titulo;
        $this->nota = $nota;
        $this->avaliador = $avaliador;
        $this->article_id = $article_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seu artigo recebeu uma nova avaliação')
            ->greeting('Olá!')
            ->line('Seu artigo "' . $this->titulo . '" recebeu uma nova nota: ' . $this->nota . ' (avaliador: ' . $this->avaliador . ')')
            ->action('Visualizar artigo', url('/artigos/' . $this->article_id . '/visualizar'))
            ->line('Obrigado por contribuir com o The English Voice!');
    }
}
