<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            switch ($role) {
                case 'Aluno':
                    return redirect()->route('dashboard');
                case 'Professor':
                    return redirect()->route('artigos.pendentes');
                case 'Admin':
                    return redirect()->route('admin.panel');
            }
        }
        // sem login ou role desconhecida segue fluxo
        return $next($request);
    }
}
