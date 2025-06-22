<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordTokenController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot_password_token');
    }

    public function generateLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Gera token manualmente (igual ao Password::broker()->createToken())
        $email = $request->input('email');
        $token = Str::random(64);
        $now = Carbon::now();

        // Remove tokens antigos
        DB::table('password_resets')->where('email', $email)->delete();

        // Salva novo token hash (padrão Laravel)
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => $now,
        ]);

        // Monta o link de reset
        $link = URL::to('/reset-password/' . $token . '?email=' . urlencode($email));

        return redirect()->back()->with('reset_link', $link);
    }
}
