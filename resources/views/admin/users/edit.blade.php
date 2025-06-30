<!DOCTYPE html>
<html lang="pt-br" class="h-100">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Artigos - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
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
        <ul class="navbar-nav ms-auto align-items-center">
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
    
    <div class="container text-start mt-4 mb-4 p-4" style="max-width: 600px;">
    <h3 class="mb-4"><i class="fas fa-user-edit text-primary me-2"></i>Editar Usuário {{ $user->name }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-user me-1"></i>Nome</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-envelope me-1"></i>E-mail</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-user-tag me-1"></i>Role</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="Aluno"   {{ old('role', $user->role)=='Aluno' ? 'selected' : '' }}>Aluno</option>
                <option value="Professor" {{ old('role', $user->role)=='Professor' ? 'selected' : '' }}>Professor</option>
                <option value="admin"   {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>admin</option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-id-card me-1"></i>RA</label>
            <input type="text" name="ra" class="form-control @error('ra') is-invalid @enderror" value="{{ old('ra', $user->ra) }}">
            @error('ra') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-university me-1"></i>Curso</label>
            <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
                <option value="">-- selecione --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->course_id }}" {{ old('course_id', $user->course_id)==$course->course_id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                @endforeach
            </select>
            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mt-4 mb-4 text-end">
            <a href="{{ url('forgot-password-token') }}" target="_blank" class="btn btn-outline-danger">
                <i class="fas fa-key me-1"></i> Redefinir senha deste usuário
            </a>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">
                <i class="fas fa-arrow-left me-1"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i>Salvar
            </button>
        </div>
    </form>
</div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>