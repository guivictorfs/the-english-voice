<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoMailable;

class ContatoController extends Controller
{
    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'assunto' => 'required|string|max:150',
            'mensagem' => 'required|string|max:2000',
        ]);

        try {
            Mail::to('theenglishvoicefatecgt@gmail.com')->send(new ContatoMailable(
                $validated['nome'],
                $validated['email'],
                $validated['assunto'],
                $validated['mensagem']
            ));
            return back()->with('success', 'Mensagem enviada com sucesso! Em breve entraremos em contato.');
        } catch (\Exception $e) {
            return back()->withErrors(['Erro ao enviar mensagem. Por favor, tente novamente.']);
        }
    }
}
