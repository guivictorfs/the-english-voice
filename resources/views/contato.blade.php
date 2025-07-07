<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ url('/') }}">Início</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('artigos.index') }}">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('help') }}">Ajuda</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('sobre') }}">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('contato') }}">Contato</a>
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
    <h1 class="mb-4 text-dark"><i class="fas fa-envelope me-2 text-primary"></i>Fale Conosco</h1>
    <hr>
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
    <form method="POST" action="{{ route('contato.enviar') }}" class="text-start mb-4">
        @csrf
        <div class="mb-3">
    <label for="nome" class="form-label"><i class="fas fa-user me-1"></i>Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label"><i class="fas fa-envelope me-1"></i>E-mail</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
</div>
<div class="mb-3">
    <label for="assunto" class="form-label"><i class="fas fa-tag me-1"></i>Assunto</label>
    <input type="text" class="form-control" id="assunto" name="assunto" value="{{ old('assunto') }}" required>
</div>
<div class="mb-3">
    <label for="mensagem" class="form-label"><i class="fas fa-comment-dots me-1"></i>Mensagem</label>
    <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required>{{ old('mensagem') }}</textarea>
</div>
<button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Enviar Mensagem</button>
    </form>
</div>

    <!-- Footer -->
    <footer class="footer bg-light py-2 mt-auto">
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
</body>
</html>