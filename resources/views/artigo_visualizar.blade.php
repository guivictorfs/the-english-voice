<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Artigos - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('students.account') }}">Meus Artigos</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
    <h2>{{ $artigo->title }}</h2>
    @if($pdfPath)
    <div class="mb-2">
        <strong>Caminho PDF:</strong> <code>{{ $pdfPath }}</code><br>
        <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir PDF em nova aba</a>
    </div>
    <iframe 
        src="{{ asset('storage/' . $pdfPath) }}" 
        width="100%" 
        height="700px" 
        style="border:1px solid #ccc;">
    </iframe>
    @else
        <div class="alert alert-warning">PDF não encontrado.</div>
    @endif
</div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
