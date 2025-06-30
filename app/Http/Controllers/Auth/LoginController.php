<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            // Redireciona com base no role do usuário
            $user = Auth::user();
            $role = $user->role;
            
            switch ($role) {
                case 'Aluno':
                    return redirect()->route('dashboard');
                case 'Professor':
                    return redirect()->route('admin.panel');
                case 'Admin':
                    return redirect()->route('admin.panel');
                default:
                    return Redirect::intended('/');
            }
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais fornecidas não correspondem a nenhum registro.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('home');
    }
}