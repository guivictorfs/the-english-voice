<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Verifica se o usuário é Admin ou Professor
        if ($user && in_array($user->role, ['Admin', 'Professor'])) {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado. Somente administradores e professores podem acessar esta área.');
    }
}
