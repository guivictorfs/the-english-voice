<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    @vite('resources/css/admin-panel.css')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
    @php $role = strtolower(Auth::user()->role ?? ''); @endphp
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
    @if($role === 'admin')
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.panel') }}">Painel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.artigos.pendentes') }}">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/courses') }}">Cursos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('keywords.index') }}">Tags</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('forbidden_words.index') }}">Palavras Proibidas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logs.index') }}">Logs</a>
            </li>
        </ul>
    @else
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item underline">
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('students.account') }}">Meus Artigos</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('artigos.postar') }}">Postar Artigo</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('articles.favorites') }}">Favoritos</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('help') }}">Ajuda</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('students.profile') }}">Conta</a>
            </li>
        </ul>
    @endif
    <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
            <form class="d-flex align-items-center" method="GET" action="{{ route('dashboard') }}" style="gap:0.25rem;">
                <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Pesquisar artigos..." aria-label="Pesquisar" value="{{ request('q') }}" style="min-width: 180px;">
                <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                @if(request('q'))
                    @php
                        $query = request()->except('q');
                        $url = route('dashboard') . ($query ? ('?' . http_build_query($query)) : '');
                    @endphp
                    <a href="{{ $url }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpar pesquisa"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </li>
    </ul>
            </div>
        </div>
    </nav>
    <div class="flex-grow-1">
        <div class="container admin-panel-main mt-4 mb-4 p-4">
            @php $role = strtolower(Auth::user()->role ?? ''); @endphp
            <h1 class="mb-5 text-center"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Painel do Administrador</h1>

            <div class="row g-4">
            <!-- Ranking de Postagens -->
            <div class="col-md-4">
                <a href="{{ route('artigos.melhores') }}" class="text-decoration-none text-dark">
                    <div class="card card-hover shadow-sm h-100 text-center p-4">
                        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Ranking de Artigos</h5>
                    </div>
                </a>
            </div>
            <!-- Artigos -->
            <div class="col-md-4">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">
                    <div class="card card-hover shadow-sm h-100 text-center p-4">
                        <i class="fas fa-newspaper fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Artigos</h5>
                    </div>
                </a>
            </div>
            <!-- Usuários -->
            <div class="col-md-4">
                @if($role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                        <div class="card card-hover shadow-sm h-100 text-center p-4">
                            <i class="fas fa-user-cog fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Gerenciar Usuários</h5>
                        </div>
                    </a>
                @else
                    <div class="card card-hover shadow-sm h-100 text-center p-4 bg-light text-muted" style="pointer-events: none; opacity: 0.6;">
                        <i class="fas fa-user-cog fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title">Gerenciar Usuários</h5>
                        <div style="font-size:0.9em;">Acesso restrito</div>
                    </div>
                @endif
            </div>
            <!-- Denúncias -->
            <div class="col-md-4">
                <a href="{{ route('admin.artigos.pendentes') }}" class="text-decoration-none text-dark">
                    <div class="card card-hover shadow-sm h-100 text-center p-4">
                        <i class="fas fa-flag fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Denúncias</h5>
                    </div>
                </a>
            </div>
            <!-- Cursos -->
            <div class="col-md-4">
                @if($role === 'admin')
                    <a href="{{ url('/admin/courses') }}" class="text-decoration-none text-dark">
                        <div class="card card-hover shadow-sm h-100 text-center p-4">
                            <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Cursos</h5>
                        </div>
                    </a>
                @else
                    <div class="card card-hover shadow-sm h-100 text-center p-4 bg-light text-muted" style="pointer-events: none; opacity: 0.6;">
                        <i class="fas fa-graduation-cap fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title">Cursos</h5>
                        <div style="font-size:0.9em;">Acesso restrito</div>
                    </div>
                @endif
            </div>
            <!-- Palavras-chave -->
            <div class="col-md-4">
                <a href="{{ route('keywords.index') }}" class="text-decoration-none text-dark">
                    <div class="card card-hover shadow-sm h-100 text-center p-4">
                        <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Tags</h5>
                    </div>
                </a>
            </div>
            <!-- Palavras Proibidas -->
            <div class="col-md-4">
                <a href="{{ route('forbidden_words.index') }}" class="text-decoration-none text-dark">
                    <div class="card card-hover shadow-sm h-100 text-center p-4">
                        <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Palavras Proibidas</h5>
                    </div>
                </a>
            </div>
            <!-- Logs do Sistema -->
            <div class="col-md-4">
                @if($role === 'admin')
                    <a href="{{ route('admin.logs.index') }}" class="text-decoration-none text-dark">
                        <div class="card card-hover shadow-sm h-100 text-center p-4">
                            <i class="fas fa-clipboard-list fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Logs do Sistema</h5>
                        </div>
                    </a>
                @else
                    <div class="card card-hover shadow-sm h-100 text-center p-4 bg-light text-muted" style="pointer-events: none; opacity: 0.6;">
                        <i class="fas fa-clipboard-list fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title">Logs do Sistema</h5>
                        <div style="font-size:0.9em;">Acesso restrito</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

<!-- FIM do conteúdo principal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Footer -->
<footer class="footer bg-light py-2 mt-auto">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        <div class="social-icons mt-3">
            <a href="https://www.linkedin.com/company/fatec-guaratinguetá/" target="_blank" class="social-link" aria-label="LinkedIn">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
            <a href="https://www.instagram.com/fatecguaratingueta/" target="_blank" class="mx-2 social-link" aria-label="Instagram">
                <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="https://www.fatecguaratingueta.edu.br" target="_blank" class="social-link" aria-label="Fatec Guaratinguetá">
                <i class="fas fa-globe fa-lg"></i>
            </a>
        </div>
    </div>
</footer>

</html>

</body>
</html>