<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContatoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nome;
    public $email;
    public $assunto;
    public $mensagem;

    public function __construct($nome, $email, $assunto, $mensagem)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }

    public function build()
    {
        return $this->subject('Contato - ' . $this->assunto)
            ->replyTo($this->email, $this->nome)
            ->view('emails.contato')
            ->with([
                'nome' => $this->nome,
                'email' => $this->email,
                'assunto' => $this->assunto,
                'mensagem' => $this->mensagem,
            ]);
    }
}
