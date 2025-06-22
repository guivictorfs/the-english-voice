<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The English Voice</title>
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4"><i class="fas fa-book-open"></i> Textos Postados</h2>
                @if($articles->count())
                    <div class="list-group">
                        @foreach($articles as $article)
                            <div class="list-group-item mb-3">
                                <h5 class="mb-1">{{ $article->title }}</h5>
                                <p class="mb-1 text-muted">
                                    Por
                                    @if($article->author && $article->author->count())
                                        {{ $article->author->pluck('name')->join(', ') }}
                                    @else
                                        Autor desconhecido
                                    @endif
                                    em {{ $article->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="mb-1">{{ Str::limit($article->content ?? '', 120) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Nenhum texto postado ainda.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
