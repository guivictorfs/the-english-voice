
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
        <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.panel') }}" class="btn btn-outline-primary d-flex align-items-center">
        <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
    </a>
    <div class="flex-grow-1 text-center">
        <h2 class="mb-2">Atividades Suspeitas</h2>
    </div>
</div>
        <form method="POST" action="{{ route('admin.checkSuspiciousVotes') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-sync-alt me-2"></i> Rodar verificação de atividades suspeitas
            </button>
        </form>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        Para atualizar a tabela, clique no botão<strong>&nbsp;Rodar verificação de atividades suspeitas</strong>. A atualização não é automática.
    </div>
    <div class="alert alert-secondary d-flex align-items-center mb-3 py-2" role="alert">
        <i class="fas fa-mouse-pointer me-2"></i>
        Clique na <strong>&nbsp;badge colorida&nbsp;</strong> na coluna <b>&nbsp;Tipo&nbsp;</b> para ver detalhes da denúncia ou notas baixas daquele usuário.
    </div>
    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="user" class="form-label mb-1">Usuário</label>
            <input type="text" class="form-control" name="user" id="user" placeholder="Nome do usuário" value="{{ request('user') }}">
        </div>
        <div class="col-md-3">
    <label for="type" class="form-label mb-1">Tipo</label>
    <select class="form-select" name="type" id="type">
        <option value="" {{ request('type') == '' ? 'selected' : '' }}>Todas</option>
        <option value="muitas_denuncias" {{ request('type') == 'muitas_denuncias' ? 'selected' : '' }}>Muitas denúncias</option>
        <option value="many_low_votes" {{ request('type') == 'many_low_votes' ? 'selected' : '' }}>Muitas notas 1</option>
        <option value="low_avg_vote" {{ request('type') == 'low_avg_vote' ? 'selected' : '' }}>Média baixa de notas</option>
    </select>
</div>
        <div class="col-md-3">
            <label for="date" class="form-label mb-1">Data</label>
            <input type="date" class="form-control" name="date" id="date" value="{{ request('date') }}">
        </div>
        <div class="col-md-2 d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filtrar</button>
    <a href="{{ route('admin.suspicious_activities.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i> Limpar</a>
</div>
    </form>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered border-dark">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($activities as $activity)
                    <tr>
                    <td>{{ $activity->user ? $activity->user->name : 'Desconhecido' }}</td>
                    <td class="text-center">
    @php
        $typeLabels = [
            'muitas_denuncias' => ['label' => 'Muitas denúncias', 'class' => 'badge bg-warning text-dark'],
            'many_low_votes' => ['label' => 'Muitas notas 1', 'class' => 'badge bg-danger'],
            'low_avg_vote' => ['label' => 'Média baixa de notas', 'class' => 'badge bg-info text-dark'],
        ];
        $type = $activity->type;
        $display = $typeLabels[$type]['label'] ?? ucfirst(str_replace('_', ' ', $type));
        $badgeClass = $typeLabels[$type]['class'] ?? 'badge bg-secondary';
    @endphp
    <a href="{{ route('admin.suspicious_activities.user_details', ['user' => $activity->user_id, 'type' => $activity->type]) }}" class="{{ $badgeClass }}" style="text-decoration:none; cursor:pointer;">{{ $display }}</a>
</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhuma atividade suspeita encontrada.</td></tr>
                @endforelse
                </tbody>
            </table>
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