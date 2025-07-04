<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminAccess
{
    public function __construct()
    {
        // Construtor
    }

    public function handle(Request $request, Closure $next)
    {
        dd(auth()->user()); // Debug temporário para verificar os dados do usuário

        $user = auth()->user();
        if ($user && $user->role === 'Admin') {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado.');
    }
}
