<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redefinir Senha - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/password-reset.css')
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
                        <a class="nav-link active" href="#">Início</a>
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

                    <!-- Se o usuário não estiver logado -->
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('login') }}">Entrar</a>
                        <a class="btn btn-success ms-2" href="{{ route('register') }}">Criar Conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-4 p-4 border border-dark">
        <h1 class="text-center mb-4 mt-4">
            <i class="fas fa-lock-open text-primary"></i> Redefinir Senha
        </h1>
    <hr>
        <div class="row justify-content-center d-flex align-items-center">
            <!-- Coluna da Imagem -->
            <div class="col-md-4 text-center">
                <img src="{{ asset('img/forgot-password.svg') }}" alt="Recuperar senha" class="img-fluid img-svg" style="max-width: 100%;">
            </div>
            <!-- HR vertical -->
            <div class="d-none d-md-block" style="width:40px;">
                <div class="vertical-hr mx-auto"></div>
            </div>
            <!-- Coluna do Formulário -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('password.update') }}" class="p-4 shadow rounded">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Nova Senha</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua nova senha" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme sua nova senha" required>
                    </div>

                    <!-- Botão de Redefinição -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sync-alt me-2"></i>Redefinir Senha
                        </button>
                    </div>

                    <!-- Links Adicionais -->
                    <div class="mt-4 text-center d-flex justify-content-center gap-2">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-1"></i>Voltar ao início
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-success">
                            <i class="fas fa-sign-in-alt me-1"></i>Voltar para login
                        </a>
                    </div>
                </form>
            </div>
            <hr class = "mt-4">
        </div>
        <div class="text-center">
            <img src="{{ asset('/img/logo-cps.png') }}" alt="The English Voice" class="img-fluid logo" style="max-width: 50em;">
    </div>
    </div>

    <!-- Mensagem de Sucesso -->
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Mensagem de Erro -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script>
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        const submitButton = document.getElementById('submitButton');
        const passwordStrengthMeter = document.getElementById('password-strength-meter');
        const passwordStrengthText = document.getElementById('password-strength-text');
        const passwordRequirements = {
            length: document.getElementById('length'),
            lowercase: document.getElementById('lowercase'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };

        const updateStrengthIndicator = (password) => {
            const strength = zxcvbn(password).score;
            const strengthLevels = ['Muito Fraca', 'Fraca', 'Moderada', 'Forte', 'Muito Forte'];
            const colors = ['#dc3545', '#fd7e14', '#ffc107', '#198754', '#20c997'];
            passwordStrengthMeter.style.width = `${(strength + 1) * 20}%`;
            passwordStrengthMeter.style.backgroundColor = colors[strength];
            passwordStrengthText.textContent = `Força da senha: ${strengthLevels[strength]}`;
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
            const isValid = Object.values(conditions).every(Boolean);
            submitButton.disabled = !isValid || (confirmPasswordField.value !== passwordField.value);
            return isValid;
        };

        passwordField.addEventListener('input', () => {
            const password = passwordField.value;
            validatePassword(password);
            updateStrengthIndicator(password);
            document.getElementById('password-strength-container').classList.toggle('d-none', !password);
        });

        confirmPasswordField.addEventListener('input', () => {
            const passwordsMatch = confirmPasswordField.value === passwordField.value;
            confirmPasswordField.classList.toggle('is-invalid', !passwordsMatch);
            confirmPasswordField.classList.toggle('is-valid', passwordsMatch);
            submitButton.disabled = !passwordsMatch || !validatePassword(passwordField.value);
        });

        document.querySelector('form').addEventListener('submit', (event) => {
            if (!validatePassword(passwordField.value) || confirmPasswordField.value !== passwordField.value) {
                event.preventDefault();
                alert('Verifique a senha antes de enviar.');
            }
        });
    </script>
</body>
</html>