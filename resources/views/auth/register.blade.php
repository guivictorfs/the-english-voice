<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Conta - The English Voice</title>

    <!-- Bootstrap e FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Estilo personalizado -->
    @vite('resources/css/register.css')
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
                        <a class="nav-link" href="#">Início</a>
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

                    <!-- Se o usuário estiver logado (exemplo de dropdown) -->
                    <!-- 
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Meu Perfil
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Meus Artigos</a></li>
                        <li><a class="dropdown-item" href="#">Configurações</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Sair</a></li>
                    </ul>
                </li>
                -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-4 p-4 border border-dark">
        <h1 class="text-center mb-4 pt-4">
            <i class="fas fa-user-plus text-success"></i> <span class="text-dark">Criar Conta</span>
        </h1>

        <div class="row justify-content-center d-flex align-items-center">
            <hr>
            <!-- Coluna da Imagem -->
            <div class="col-md-4 text-center me-5">
                <img src="{{ asset('img/access-account.svg') }}" alt="Acessar conta" class="img-fluid img-svg" style="max-width: 100%;">
            </div>
            <!-- HR vertical -->
            <div class="d-none d-md-block" style="width:40px;">
                <div class="vertical-hr mx-auto"></div>
            </div>
            <!-- Coluna do Formulário -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('register') }}" class="p-4 shadow rounded border border-dark" id="registerForm">
                    @csrf
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="fas fa-user"></i> Nome Completo</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome"
                            required value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- E-mail -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail institucional</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail"
                            required value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Senha</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Digite sua senha" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <!-- Medidor de força -->
                        <div id="password-strength-container" class="d-none">
                            <div class="progress mt-2">
                                <div id="password-strength-meter" class="progress-bar" role="progressbar"
                                    style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
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

                    <!-- Confirmar senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirme sua senha" required>
                    </div>

                    <!-- Tipo de Usuário -->
                    <div class="mb-3">
                        <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Tipo de Usuário</label>
                        <input type="text" name="role" id="role" class="form-control" value="Aluno" readonly>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- RA -->
                    <div class="mb-3">
                        <label for="ra" class="form-label"><i class="fas fa-id-badge"></i> RA</label>
                        <input type="text" name="ra" id="ra" class="form-control" placeholder="Digite seu RA"
                            maxlength="20" value="{{ old('ra') }}">
                        @error('ra')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Curso -->
                    <div class="mb-3">
                        <label for="course" class="form-label"><i class="fas fa-graduation-cap"></i> Curso</label>
                        <select name="course" id="course" class="form-select" required>
                            <option value="">Selecione seu curso</option>
                            @foreach (App\Enums\Course::cases() as $course)
                                @if($course->value !== 'Professor' && $course->value !== 'Administrador')
                                    <option value="{{ $course->value }}" {{ old('course') === $course->value ? 'selected' : '' }}>
                                        {{ $course->value }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('course')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Botão -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitButton">
                            <i class="fas fa-check"></i> Cadastrar
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="text-primary text-decoration-none home-link me-2"><i class="fas fa-home"></i> Voltar ao início</a>
                        <a href="{{ route('login') }}" class="text-success text-decoration-none login-link"><i class="fas fa-sign-in-alt"></i> Já possuo conta</a>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="text-center pb-4">
            <img src="{{ asset('/IMG/logo-cps.png') }}" alt="Logotipo Centro Paula Souze" class="img-fluid img-logo">
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-light py-2 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
            <div class="social-icons mt-3">
                <a href="https://www.linkedin.com/company/fatec-guaratinguetá/" target="_blank" class="social-link"
                    aria-label="LinkedIn">
                    <i class="fab fa-linkedin fa-lg"></i>
                </a>
                <a href="https://www.instagram.com/fatecguaratingueta/" target="_blank" class="mx-2 social-link"
                    aria-label="Instagram">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
                <a href="https://www.fatecguaratingueta.edu.br" target="_blank" class="social-link"
                    aria-label="Fatec Guaratinguetá">
                    <i class="fas fa-globe fa-lg"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/zxcvbn@4.4.2/dist/zxcvbn.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let isVerified = false; // Variável para rastrear se a verificação foi concluída

    document.getElementById('role').addEventListener('change', function () {
        const role = this.value;

        if (role === 'Professor' || role === 'admin') {
            isVerified = false; // Reseta a verificação ao mudar para um tipo protegido
            const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
            modal.show();

            document.getElementById('verifyButton').addEventListener('click', async function () {
                const enteredCode = document.getElementById('verification_code').value;

                const response = await fetch('/validate-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ role, code: enteredCode }),
                });

                const result = await response.json();

                if (result.valid) {
                    isVerified = true; // Define como verdadeiro ao validar o código
                    const modal = bootstrap.Modal.getInstance(document.getElementById('verificationModal'));
                    modal.hide();
                    document.getElementById('error-message').style.display = 'none';
                } else {
                    isVerified = false;
                    document.getElementById('error-message').style.display = 'block';
                }
            });

            document.querySelector('.btn-close, .btn-danger').addEventListener('click', function () {
                // Reseta o campo de seleção ao cancelar
                document.getElementById('role').value = '';
            });
        } else {
            isVerified = true; // Para usuários que não precisam de verificação
            document.getElementById('ra-container').style.display = role === 'Aluno' ? 'block' : 'none';
            if (role !== 'Aluno') {
                document.getElementById('ra').value = ''; // Limpa o RA se não for Aluno
            }
        }
    });

    document.getElementById("role").addEventListener("change", function () {
        var raContainer = document.getElementById("ra-container");
        raContainer.style.display = (this.value === "Aluno") ? "block" : "none";
    });

    // Inicialização do Bootstrap Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

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

    document.getElementById('registerForm').addEventListener('submit', (event) => {
        if (!validatePassword(passwordField.value) || confirmPasswordField.value !== passwordField.value) {
            event.preventDefault();
            alert('Verifique a senha antes de enviar.');
        }
    });
    </script>
</body>

</html>
