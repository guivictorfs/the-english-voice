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
</head>

<body>
    <div class="container">
        <div class="text-center">
            <img src="{{ asset('/img/logo-cps.png') }}" alt="The English Voice" class="img-fluid" style="max-width: 50em;">
        </div>
        <h1 class="text-center mb-4 mt-4">
            <i class="fas fa-key text-primary"></i> Redefinir Senha
        </h1>

        <div class="row justify-content-center d-flex align-items-center">
            <!-- Coluna da Imagem -->
            <div class="col-md-4 text-center me-5">
                <img src="{{ asset('img/reset-password.svg') }}" alt="Redefinir senha" class="img-fluid" style="max-width: 100%;">
            </div>

            <!-- Coluna do Formulário -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('password.update') }}" class="p-4 shadow rounded">
                    @csrf
                    
                    <!-- Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required value="{{ $email ?? old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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

                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme sua nova senha" required>
                    </div>

                    <!-- Botão de Redefinição -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary w-100" id="submitButton" disabled>
                            <i class="fas fa-sync-alt me-2"></i>Redefinir Senha
                        </button>
                    </div>

                    <!-- Links Adicionais -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt me-1"></i>Voltar para login
                        </a>
                    </div>
                </form>
            </div>
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