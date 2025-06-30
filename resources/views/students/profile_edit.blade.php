<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - The English Voice</title>
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
                        <a class="nav-link active" href="{{ route('students.profile') }}">Conta</a>
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

    <div class="container mt-4 pb-4">
    <h2 class="mb-4 pt-4 fs-2">Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fs-4">Foto de Perfil</label>
            @if($user->profile_photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Foto atual" class="img-thumbnail" style="max-width:120px;">
                </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small class="form-text text-muted">JPG ou PNG até 2 MB.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">RA</label>
            <input type="text" class="form-control" value="{{ $user->ra }}" disabled>
            <small class="form-text text-muted">Para alterar seu RA, entre em contato com o administrador.</small>

        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Curso</label>
            <input type="text" class="form-control" value="{{ $courses[$user->course_id] ?? '' }}" disabled>
            <small class="form-text text-muted">Para alterar curso, entre em contato com o administrador.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Perfil</label>
            <input type="text" class="form-control" value="{{ $user->role }}" disabled>
            <small class="form-text text-muted">Para alterar o tipo de perfil, entre em contato com o administrador.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Conta criada em</label>
            <input type="text" class="form-control" value="{{ $user->created_at->format('d/m/Y H:i') }}" disabled>
        </div>

        <hr>
        <h5 class="fs-4">Alterar Senha</h5>
        <div class="mb-3">
            <label class="form-label fs-5">Nova Senha</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Digite a nova senha">
            <!-- Medidor de força -->
            <div id="password-strength-container" class="d-none">
                <div class="progress mt-2">
                    <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small id="password-strength-text" class="form-text text-muted"></small>
                <ul class="mt-2">
                    <li id="length" class="text-danger">Mínimo 8 caracteres</li>
                    <li id="lowercase" class="text-danger">Letra minúscula</li>
                    <li id="uppercase" class="text-danger">Letra maiúscula</li>
                    <li id="number" class="text-danger">Número</li>
                    <li id="special" class="text-danger">Caractere especial</li>
                </ul>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fs-5">Confirmar Nova Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme a nova senha">
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-danger me-5">Cancelar</a>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>

@push('scripts')
<script>
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('password_confirmation');
const strengthMeter = document.getElementById('password-strength-meter');
const strengthText = document.getElementById('password-strength-text');
const passwordRequirements = {
    length: document.getElementById('length'),
    lowercase: document.getElementById('lowercase'),
    uppercase: document.getElementById('uppercase'),
    number: document.getElementById('number'),
    special: document.getElementById('special')
};

const updateStrengthIndicator = (password) => {
    const strength = [/[a-z]/, /[A-Z]/, /[0-9]/, /[^A-Za-z0-9]/].reduce((acc, regex) => acc + regex.test(password), 0);
    const width = Math.min(100, (strength + (password.length >= 8 ? 1 : 0)) * 20);
    strengthMeter.style.width = width + '%';
    strengthMeter.className = 'progress-bar';
    if (width < 40) strengthMeter.classList.add('bg-danger');
    else if (width < 80) strengthMeter.classList.add('bg-warning');
    else strengthMeter.classList.add('bg-success');
    strengthText.textContent = width < 40 ? 'Senha fraca' : (width < 80 ? 'Média' : 'Forte');
};

const validatePassword = (password) => {
    const conditions = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };
    for (const [key, value] of Object.entries(conditions)) {
        passwordRequirements[key].classList.toggle('text-success', value);
        passwordRequirements[key].classList.toggle('text-danger', !value);
    }
    return Object.values(conditions).every(Boolean);
};

passwordField.addEventListener('input', () => {
    const pwd = passwordField.value;
    document.getElementById('password-strength-container').classList.toggle('d-none', !pwd);
    validatePassword(pwd);
    updateStrengthIndicator(pwd);
});

confirmPasswordField.addEventListener('input', () => {
    const match = confirmPasswordField.value === passwordField.value;
    confirmPasswordField.classList.toggle('is-invalid', !match);
    confirmPasswordField.classList.toggle('is-valid', match);
});
</script>
@endpush

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

