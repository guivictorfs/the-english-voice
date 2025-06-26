<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The English Voice')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                        <a class="nav-link active" href="/">Início</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('login') }}">Entrar</a>
                        <a class="btn btn-success ms-2" href="{{ route('register') }}">Criar Conta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        @yield('content')
    </div>
    <footer class="footer bg-light py-2">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
            <div class="social-icons mt-3">
                <a href="https://www.linkedin.com/company/fatec-guaratinguet%C3%A1/" target="_blank" class="social-link" aria-label="LinkedIn">
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
</body>
</html>
