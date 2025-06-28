@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">Painel do Administrador</h1>

    <div class="row g-4">
        <!-- Usuários -->
        <div class="col-md-4">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Gerenciar Usuários</h5>
                </div>
            </a>
        </div>
        <!-- Denúncias -->
        <div class="col-md-4">
            <a href="{{ route('admin.artigos.pendentes') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-flag fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Denúncias</h5>
                </div>
            </a>
        </div>
        <!-- Cursos -->
        <div class="col-md-4">
            <a href="{{ url('/admin/courses') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Cursos</h5>
                </div>
            </a>
        </div>
        <!-- Palavras-chave -->
        <div class="col-md-4">
            <a href="{{ route('keywords.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Palavras-chave</h5>
                </div>
            </a>
        </div>
        <!-- Palavras Proibidas -->
        <div class="col-md-4">
            <a href="{{ route('forbidden_words.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Palavras Proibidas</h5>
                </div>
            </a>
        </div>
        <!-- Logs do Sistema -->
        <div class="col-md-4">
            <a href="{{ route('admin.logs.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 text-center p-4">
                    <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title">Logs do Sistema</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
