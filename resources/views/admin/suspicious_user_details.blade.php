
<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades suspeitas - The English Voice</title>
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
        <ul class="navbar-nav ms-auto align-items-center ">
            <li class="nav-item underline">
                <a class="nav-link active" href="{{ route('dashboard') }}">Artigos</a>
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
                <input class="form-control form-control-sm me-2 border border-dark" type="search" name="q" placeholder="Pesquisar artigos..." aria-label="Pesquisar" value="{{ request('q') }}" style="min-width: 180px;">
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

    <div class="container mt-4 mb-4 p-4 border border-dark">
    <div class="row align-items-center mb-4">
        <div class="col-auto">
            <a href="{{ route('admin.suspicious_activities.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
        </div>
        <div class="col text-center">
            <h3 class="mb-0">Atividades suspeitas de <span class="text-primary">{{ $user->name }}</span></h3>
        </div>
        <div class="col-auto"></div> <!-- Para centralizar -->
    </div>
    <h5 class="mb-3">
        Tipo: 
        @if($tipo === 'muitas_denuncias')
            <span class="badge bg-warning text-dark">Muitas denúncias</span>
        @elseif($tipo === 'many_low_votes')
            <span class="badge bg-danger">Muitas notas 1</span>
        @elseif($tipo === 'low_avg_vote')
            <span class="badge bg-info text-dark">Média baixa de notas</span>
        @else
            <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$tipo)) }}</span>
        @endif
    </h5>
    @if($artigos->isEmpty())
        <div class="alert alert-info">Nenhum artigo relacionado encontrado.</div>
    @else
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Título do Artigo</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($artigos as $artigo)
                        @if($artigo)
                        <tr>
                            <td>{{ $artigo->title ?? '-' }}</td>
                            <td>{{ isset($artigo->created_at) ? Carbon::parse($artigo->created_at)->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $artigo->status ?? '-' }}</td>
                            <td>
                                @if($artigo && isset($artigo->article_id))
                                    <a href="{{ route('artigos.show', $artigo->article_id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Ver
                                    </a>
                                @else
                                    <span class="text-muted">Artigo não encontrado</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
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