<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
<body>
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
                <a class="nav-link" href="{{ route('admin.panel') }}">Painel</a>
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
                <a class="nav-link" href="{{ route('dashboard') }}">Artigos</a>
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

    <div class="container mt-5 p-4">
    <div class="d-flex align-items-center mb-4 pt-4">
        <a href="javascript:history.back()" class="btn btn-outline-primary d-flex align-items-center mb-0">
            <i class="fas fa-arrow-left me-2"></i> Voltar
        </a>
        <h2 class="mb-0 text-primary mx-auto"><i class="fas fa-trophy text-warning me-2"></i> Artigos com Melhor Nota Média</h2>
    </div>
<p class="mb-4 text-muted text-center" style="max-width:700px; margin-left:auto; margin-right:auto;">
    <i class="fas fa-info-circle me-1"></i>
    Só aparecem artigos com pelo menos 5 avaliações. O ranking usa média ponderada bayesiana, considerando a média geral do site, para garantir justiça entre artigos muito avaliados e recém-avaliados.
</p>
    <div class="row">
        @forelse ($artigos as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i>
                                {{ number_format($artigo->avaliacoes_avg_nota, 2, ',', '.') }}/5
                            </span>
                            <span class="text-muted ms-2">({{ $artigo->avaliacoes_count }} avaliações)</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-warning btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado ainda.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-success"><i class="fas fa-fire text-danger"></i> Melhor nota do mês</h3>
    <div class="row">
        @forelse ($maisAvaliadosMes as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-success text-white">
    <i class="fas fa-star"></i>
    Nota ponderada do mês: {{ number_format($artigo->media_ponderada_mes, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-success btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado neste mês.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-primary"><i class="fas fa-calendar-alt text-info"></i> Melhor nota do ano</h3>
    <div class="row">
        @forelse ($maisAvaliadosAno as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-info shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-info text-white">
    <i class="fas fa-star"></i>
    Nota ponderada do ano: {{ number_format($artigo->media_ponderada_ano, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-info btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado neste ano.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-warning"><i class="fas fa-list-ol text-warning"></i> Melhor nota geral</h3>
    <div class="row">
        @forelse ($maisAvaliadosGeral as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark">
    <i class="fas fa-star"></i>
    Nota ponderada geral: {{ number_format($artigo->media_ponderada, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-warning btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado no geral.</div>
            </div>
        @endforelse
    </div>
</div>
<!-- FIM do conteúdo principal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Footer -->
<footer class="footer bg-light py-2">
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