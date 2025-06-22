<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/help', function () {
    return view('help');
})->name('help');



Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('register', function () {
    return view('auth.register');
})->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'reset']);



Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/logout', function () {
    auth()->logout();  // Desloga o usuário
    session()->invalidate();  // Limpa a sessão
    session()->regenerateToken();  // Gera novo token
    return redirect()->route('home');  // Redireciona para a página inicial ou outra rota
})->name('logout');

Route::post('/validate-code', function (Illuminate\Http\Request $request) {
    $role = $request->input('role');
    $code = $request->input('code');
    $codes = config('codes');

    if (isset($codes[$role]) && Hash::check($code, $codes[$role])) {
        return response()->json(['valid' => true]);
    }

    return response()->json(['valid' => false], 401);
});

// Dashboard (protegido por autenticação)
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
