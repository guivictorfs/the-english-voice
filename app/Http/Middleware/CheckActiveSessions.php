<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckActiveSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        Log::info('Middleware CheckActiveSessions foi executado');
        // Inicia a sessão se não estiver iniciada
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }

        if (Auth::check()) {
            // Verifica se há outra sessão ativa para este usuário
            $activeSessions = DB::table('sessions')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $request->session()->getId())
                ->where('last_activity', '>', now()->subMinutes(30))
                ->get();

            if ($activeSessions->count() > 0) {
                // Salva a mensagem temporariamente
                $warningMessage = 'Sua conta foi acessada em outro dispositivo. Você foi desconectado deste dispositivo.';
                
                // Invalida todas as sessões do usuário
                DB::table('sessions')
                    ->where('user_id', Auth::id())
                    ->delete();

                // Log para depuração
                \Log::info('CheckActiveSessions: sessão concorrente detectada para o usuário ' . Auth::id());
                \Log::info('CheckActiveSessions: mensagem de warning -> ' . $warningMessage);

                // Limpa a sessão atual
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                \Log::info('CheckActiveSessions: redirect para login com warning');
                // Redireciona para a página de login com mensagem flash
                return redirect()->route('login')->with('warning', $warningMessage);

            }

            // Atualiza o timestamp da sessão atual
            $request->session()->put('last_activity', now());
        }

        return $next($request);
    }
}
