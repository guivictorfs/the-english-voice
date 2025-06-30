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

Route::get('/device-conflict', function () {
    return view('auth.device_conflict');
})->name('device.conflict');

// Sistema de Avaliação de Artigos
Route::post('/avaliacao', [AvaliacaoController::class, 'store'])->name('avaliacao.store');

// API autocomplete keywords
Route::get('/api/keywords', [KeywordController::class, 'index']);
// Verifica palavras proibidas
Route::post('/api/check-forbidden-words', [ForbiddenWordController::class, 'check'])->name('check.forbidden.words');

// (Opcional) API autocomplete authors
// Route::get('/api/authors', [AuthorController::class, 'index']);

Route::get('/help', function () {
    return view('help');
})->name('help');

// Rotas de keywords (apenas para admin)
Route::middleware(['auth', \App\Http\Middleware\VerifyAdminAccess::class])->group(function () {
    Route::get('/keywords', [\App\Http\Controllers\KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [\App\Http\Controllers\KeywordController::class, 'store'])->name('keywords.store');
    Route::delete('/keywords/{id}', [\App\Http\Controllers\KeywordController::class, 'destroy'])->name('keywords.destroy');
});

// Rotas de palavras proibidas (apenas para admin)
Route::middleware(['auth', \App\Http\Middleware\VerifyAdminAccess::class])->group(function () {
    Route::get('/forbidden_words', [\App\Http\Controllers\ForbiddenWordController::class, 'index'])->name('forbidden_words.index');
    Route::post('/forbidden_words', [\App\Http\Controllers\ForbiddenWordController::class, 'store'])->name('forbidden_words.store');
    Route::delete('/forbidden_words/{id}', [\App\Http\Controllers\ForbiddenWordController::class, 'destroy'])->name('forbidden_words.destroy');
});

// Rota de teste para diagnóstico do middleware
Route::get('/teste-admin', function () {
    return 'Você é admin!';
})->middleware(['auth', \App\Http\Middleware\VerifyAdminAccess::class]);



Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('register', function () {
    return view('auth.register');
})->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rota de logout (POST)
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->name('logout.post')->middleware('auth');

// Rota de logout (GET)
Route::get('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->name('logout')->middleware('auth');

// Redefinição de senha exibindo o link/token na tela
Route::get('forgot-password-token', [\App\Http\Controllers\Auth\PasswordTokenController::class, 'showForm'])
    ->middleware([\App\Http\Middleware\VerifyAdminAccess::class])
    ->name('password.token.form');

Route::post('forgot-password-token', [\App\Http\Controllers\Auth\PasswordTokenController::class, 'generateLink'])
    ->middleware([\App\Http\Middleware\VerifyAdminAccess::class])
    ->name('password.token.link');

// Rotas AJAX para redefinição de senha personalizada
Route::middleware(['auth', \App\Http\Middleware\CheckAdminAccess::class])->group(function () {
    Route::get('forgot-password-ajax', function() {
        return view('auth.forgot_password_ajax');
    })->name('password.request.ajax');

    Route::get('reset-password-ajax/{token}', function($token) {
        $email = request('email');
        return view('auth.reset_password_ajax', compact('token', 'email'));
    })->name('password.reset.ajax');
});

// Rotas padrão (backend Laravel)
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::get('/artigos', [App\Http\Controllers\ArtigoController::class, 'index'])->name('dashboard');

// Painel do aluno - Meus Artigos
use App\Http\Controllers\StudentController;
Route::middleware(['auth'])->get('/students/account', [StudentController::class, 'account'])->name('students.account');
Route::middleware(['auth'])->delete('/artigos/{article}/excluir', [StudentController::class, 'destroy'])->name('artigos.excluir');

// Perfil do aluno
Route::middleware(['auth', 'check.active.sessions'])->group(function () {
    Route::get('/students/profile', [StudentController::class, 'profile'])->name('students.profile');
    Route::post('/students/profile/update', [StudentController::class, 'updateProfile'])->name('students.profile.update');
    Route::put('/students/profile', [StudentController::class, 'updateProfile'])->name('students.profile.update');
});

// Painel do Administrador
Route::middleware(['auth', \App\Http\Middleware\VerifyAdminAccess::class])->prefix('admin')->name('admin.')->group(function(){
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
    // Revisão de artigos denunciados
    Route::get('/artigos_pendentes', [\App\Http\Controllers\ArtigoController::class, 'pendentes'])->name('artigos.pendentes');
    Route::post('/artigos/{article}/aprovar', [\App\Http\Controllers\ArtigoController::class, 'aprovar'])->name('artigos.aprovar');
    Route::delete('/artigos/{article}/excluir', [\App\Http\Controllers\ArtigoController::class, 'excluir'])->name('artigos.excluir');
    // Palavras proibidas
    Route::get('/forbidden_words', [ForbiddenWordController::class, 'index'])->name('forbidden_words.index');
    Route::post('/forbidden_words', [ForbiddenWordController::class, 'store'])->name('forbidden_words.store');
    Route::delete('/forbidden_words/{id}', [ForbiddenWordController::class, 'destroy'])->name('forbidden_words.destroy');

    // Adicionando a rota de palavras proibidas dentro do grupo admin
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        // Revisão de artigos denunciados
        Route::get('/artigos_pendentes', [\App\Http\Controllers\ArtigoController::class, 'pendentes'])->name('admin.artigos.pendentes');
        Route::post('/artigos/{article}/aprovar', [\App\Http\Controllers\ArtigoController::class, 'aprovar'])->name('admin.artigos.aprovar');
        Route::delete('/artigos/{article}/excluir', [\App\Http\Controllers\ArtigoController::class, 'excluir'])->name('admin.artigos.excluir');
    });
});

// Página para postar artigo
Route::get('/artigos/postar', function () {
    return view('artigo_postar');
})->name('artigos.postar');

// Lista de artigos
Route::get('/artigos', [App\Http\Controllers\ArtigoController::class, 'index'])->name('artigos.index');

// Lista de melhores artigos
Route::get('/artigos/melhores', [App\Http\Controllers\ArtigoController::class, 'melhores'])->name('artigos.melhores');

// (Opcional) Rota para processar o envio do artigo
Route::post('/artigos', [App\Http\Controllers\ArtigoController::class, 'store'])->middleware('auth')->name('artigos.store');

// Visualizar artigo (PDF)
Route::get('/artigos/{article}/visualizar', [App\Http\Controllers\ArtigoController::class, 'visualizar'])->middleware('auth')->name('artigos.visualizar');

// Baixar PDF do artigo
Route::get('/artigos/{article}/pdf', [App\Http\Controllers\ArtigoController::class, 'gerarPdf'])->middleware('auth')->name('artigos.pdf');

// Exibir artigo individual
Route::get('/artigos/{article}', [App\Http\Controllers\ArtigoController::class, 'show'])
    ->middleware('auth')
    ->name('artigos.show');

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

// Artigos (protegido por autenticação)
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Favoritar/desfavoritar artigos e listar favoritos
Route::middleware(['auth'])->group(function () {
    Route::post('/artigos/{article}/favorite', [\App\Http\Controllers\FavoriteController::class, 'store'])->name('articles.favorite');
    Route::delete('/artigos/{article}/favorite', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('articles.unfavorite');
    Route::get('/favoritos', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('articles.favorites');
});