<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
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
                        <a class="nav-link active" href="{{ route('admin.logs.index') }}">Logs</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container p-4 flex-grow-1 mt-4 mb-5">
    <div class="d-flex align-items-center mb-4">
        <div class="flex-shrink-0">
            <a href="{{ route('admin.panel') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Voltar ao Painel</a>
        </div>
        <div class="flex-grow-1 text-center">
            <h3><i class="fas fa-file-alt me-2 text-secondary"></i>Logs do Sistema</h3>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Data Inicial</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Data Final</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Ação</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Buscar por ação" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Usuário</label>
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Buscar por usuário" class="form-control">
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter me-1"></i>Filtrar</button>
            @if(request()->has(['start_date', 'end_date', 'action', 'user']))
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i>Limpar Filtros</a>
            @endif
        </div>
    </form>

    <!-- Tabela de Logs -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID Registro</th>
            <th>Data/Hora</th>
            <th>Ação</th>
            <th>Usuário</th>
            <th>Email</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->record_id ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($log->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->performed_by ?? '-' }}</td>
                <td>{{ $log->email ?? '-' }}</td>
                <td>{{ $log->description }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Nenhum log encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
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