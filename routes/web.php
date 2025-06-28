<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeywordController;
// use App\Http\Controllers\Api\AuthorController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ForbiddenWordController;
use App\Http\Controllers\AvaliacaoController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Sistema de Avaliação de Artigos
Route::post('/avaliacao', [AvaliacaoController::class, 'store'])->name('avaliacao.store');

// API autocomplete keywords
Route::get('/api/keywords', [KeywordController::class, 'index']);

// (Opcional) API autocomplete authors
// Route::get('/api/authors', [AuthorController::class, 'index']);

Route::get('/help', function () {
    return view('help');
})->name('help');



Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('register', function () {
    return view('auth.register');
})->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Redefinição de senha exibindo o link/token na tela
Route::get('forgot-password-token', [\App\Http\Controllers\Auth\PasswordTokenController::class, 'showForm'])->name('password.token.form');
Route::post('forgot-password-token', [\App\Http\Controllers\Auth\PasswordTokenController::class, 'generateLink'])->name('password.token.link');

// Rotas AJAX para redefinição de senha personalizada
Route::get('forgot-password-ajax', function() {
    return view('auth.forgot_password_ajax');
})->name('password.request.ajax');

Route::get('reset-password-ajax/{token}', function($token) {
    $email = request('email');
    return view('auth.reset_password_ajax', compact('token', 'email'));
})->name('password.reset.ajax');

// Rotas padrão (backend Laravel)
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Painel do aluno - Meus Artigos
use App\Http\Controllers\StudentController;
Route::middleware(['auth'])->get('/students/account', [StudentController::class, 'account'])->name('students.account');
Route::middleware(['auth'])->delete('/artigos/{article}/excluir', [StudentController::class, 'destroy'])->name('artigos.excluir');

// Perfil do aluno
Route::middleware(['auth'])->get('/students/profile', [StudentController::class, 'profile'])->name('students.profile');

// Painel do Administrador
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', function(){ return view('admin.admin_panel'); })->name('panel');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{id}/logs', [\App\Http\Controllers\Admin\UserController::class, 'logs'])->name('users.logs');
    Route::post('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::get('/articles', [\App\Http\Controllers\Admin\AdminPanelController::class, 'articles'])->name('articles.index');
    Route::get('/reports', [\App\Http\Controllers\Admin\AdminPanelController::class, 'reports'])->name('reports.index');
    // Rotas de cursos
    Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'store'])->name('courses.store');
    // fallback para edição via POST caso PUT não seja suportado
    Route::post('/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update');
    Route::put('/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update.put');
    Route::delete('/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/keywords', [\App\Http\Controllers\Admin\AdminPanelController::class, 'keywords'])->name('keywords.index');
    Route::get('/logs', [\App\Http\Controllers\Admin\AdminPanelController::class, 'logs'])->name('logs.index');
});
Route::middleware(['auth'])->post('/students/profile', [StudentController::class, 'updateProfile'])->name('students.profile.update');



Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Redirecionamento pós-login conforme role
Route::middleware(['auth', \App\Http\Middleware\RoleRedirect::class])->get('/home', function(){
    // fallback caso middleware não redirecione
    return view('welcome');
});

Route::get('/logout', function () {
    auth()->logout();  // Desloga o usuário
    session()->invalidate();  // Limpa a sessão
    session()->regenerateToken();  // Gera novo token
    return redirect()->route('home');  // Redireciona para a página inicial ou outra rota
})->name('logout');

// Página para postar artigo
Route::get('/artigos/postar', function () {
    return view('artigo_postar');
})->middleware('auth')->name('artigos.postar');

// (Opcional) Rota para processar o envio do artigo
Route::post('/artigos', [App\Http\Controllers\ArtigoController::class, 'store'])->middleware('auth')->name('artigos.store');

// Visualizar artigo (PDF)
Route::get('/artigos/{article}/visualizar', [App\Http\Controllers\ArtigoController::class, 'visualizar'])->middleware('auth')->name('artigos.visualizar');

// Editar artigo
Route::get('/artigos/{article}/edit', [App\Http\Controllers\ArtigoController::class, 'edit'])->middleware('auth')->name('artigos.edit');
Route::post('/artigos/{article}/edit', [App\Http\Controllers\ArtigoController::class, 'update'])->middleware('auth')->name('artigos.update');

// Denunciar artigo
Route::post('/artigos/{article}/denunciar', [App\Http\Controllers\ArtigoController::class, 'denunciar'])->middleware('auth')->name('artigos.denunciar');

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

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Painel de tags/keywords
    Route::get('/keywords', [\App\Http\Controllers\KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [\App\Http\Controllers\KeywordController::class, 'store'])->name('keywords.store');
    Route::delete('/keywords/{id}', [\App\Http\Controllers\KeywordController::class, 'destroy'])->name('keywords.destroy');
    // Revisão de artigos denunciados
    Route::get('/artigos_pendentes', [\App\Http\Controllers\ArtigoController::class, 'pendentes'])->name('admin.artigos.pendentes');
    Route::post('/artigos/{article}/aprovar', [\App\Http\Controllers\ArtigoController::class, 'aprovar'])->name('admin.artigos.aprovar');
    Route::delete('/artigos/{article}/excluir', [\App\Http\Controllers\ArtigoController::class, 'excluir'])->name('admin.artigos.excluir');
    Route::get('/forbidden_words', [ForbiddenWordController::class, 'index'])->name('forbidden_words.index');
    Route::post('/forbidden_words', [ForbiddenWordController::class, 'store'])->name('forbidden_words.store');
    Route::delete('/forbidden_words/{id}', [ForbiddenWordController::class, 'destroy'])->name('forbidden_words.destroy');
});