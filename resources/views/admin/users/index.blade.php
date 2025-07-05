
<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários Cadastrados - The English Voice</title>
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
                        <a class="nav-link active" href="{{ route('admin.users.index') }}">Usuários</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 p-4 border border-dark">
    <div class="d-flex align-items-center mb-4">
        <div class="flex-shrink-0">
            <a href="{{ route('admin.panel') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Voltar ao Painel</a>
        </div>
        <div class="flex-grow-1 text-center">
            <h2><i class="fas fa-users me-2 text-primary"></i>Usuários cadastrados</h2>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3" action="{{ route('admin.users.index') }}">
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nome ou email" class="form-control">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1 text-white"></i> Pesquisar</button>
        </div>
        @if(request('q'))
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            @if($user->profile_photo)
                                @php
                                    $photo = $user->profile_photo;
                                    if (!Str::contains($photo, '.')) {
                                        $photo .= '.jpg'; // fallback para .jpg se não houver extensão
                                    }
                                @endphp
                                <img src="{{ asset('storage/'.$photo) }}" alt="avatar" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-2x text-secondary"></i>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge {{ $user->getBadgeColor() }}">{{ $user->role }}</span></td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    
                                        <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                                <a href="{{ route('admin.users.logs', $user->id) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-list-alt me-1"></i> Logs
                                </a>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
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