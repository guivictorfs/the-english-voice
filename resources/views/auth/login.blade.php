<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/login.css')
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
                        <a class="nav-link" href="{{ url('/') }}">Início</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('login') }}">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('help') }}">Ajuda</a>
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
    <div class="container container-flex mt-4 mb-4 p-4 border border-dark">
        <h1 class="text-center mb-4 mt-4">
            <i class="fas fa-sign-in-alt text-primary"></i> <span class="text-dark">Entrar</span>
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
                <!-- Notificação de logout por sessão concorrente -->
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" class="p-4 shadow rounded border border-dark">
                    @csrf
                    

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required value="{{ old('email') }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Senha</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua senha" required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                                        
                    <!-- Botão de Login -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>
                    
                    <!-- Links Adicionais -->
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-danger text-decoration-none forgot-password-link">
                            <i class="fas fa-key me-1"></i>Esqueceu sua senha?
                        </a>
                        <br>
                        <a href="{{ route('register') }}" class="text-success text-decoration-none register-link">
                            <i class="fas fa-user-plus me-1"></i>Não tem uma conta? Crie uma
                        </a>
                    </div>
                </form>
            </div>
            <hr class="mt-5">
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
        <footer class="footer bg-light py-2">
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
