<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Redefinição de senha - The English Voice')
            ->greeting('Olá!')
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.')
            ->action('Redefinir senha', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Se você não solicitou a redefinição de senha, nenhuma ação é necessária.')
            ->line('Se precisar de ajuda, entre em contato com o suporte do The English Voice.');
    }
}
