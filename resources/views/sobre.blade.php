<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - The English Voice</title>
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
                        <a class="nav-link active" href="{{ route('sobre') }}">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('contato') }}">Contato</a>
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
        <h1 class="mb-4 text-dark"><i class="fas fa-info-circle me-2 text-primary"></i>Sobre</h1>
        <hr>
        <p>A plataforma que abriga o The English Voice é um projeto desenvolvido por alunos e professores da Fatec Guaratinguetá, com o objetivo de criar um ambiente de aprendizado de inglês de forma lúdica e interativa.A plataforma The English Voice foi criada como uma iniciativa para estimular o aprendizado da língua inglesa e a produção acadêmica entre os alunos. Inspirada pela necessidade de um ambiente mais flexível e adaptado às realidades pedagógicas da instituição, oferecendo uma experiência mais moderna, intuitiva e segura.
        </p>
        <p>A plataforma oferece recursos como submissão de artigos, avaliação, comentários e denúncias. Tudo isso com foco em usabilidade, acessibilidade, segurança e no protagonismo estudantil.
        </p>
        <p>Além disso, o projeto busca criar um ambiente ético e seguro para o desenvolvimento da escrita acadêmica, do pensamento crítico e da autonomia dos estudantes.
        </p>
        <p>The English Voice é mais do que uma plataforma: é um espaço construído para valorizar a produção intelectual dos alunos e fortalecer a comunidade acadêmica por meio da linguagem e da tecnologia.</p>
        <hr>
        <h2 class="mb-3 text-dark text-center"><i class="fas fa-users me-2 text-primary"></i>Equipe</h2>
        <div class="d-flex justify-content-center">
            <ul class="text-start" style="max-width:320px;">
                <li>Prof. esp. Cristóvão Cunha</li>
                <li>Prof. esp. Dorotéia Soares</li>
                <li>Aluno Guilherme Santos</li>
                <li>Aluno Pedro Fais</li>
                <li>Prof. Dra. Taciana Coelho</li>
            </ul>
        </div>
        <hr>
        <h2 class="mb-3 text-dark"><i class="fas fa-envelope me-2 text-primary"></i>Contato</h2>
        <p>Para entrar em contato conosco, basta enviar uma mensagem para o e-mail <a href="mailto:theenglishvoice@gmail.com">theenglishvoice@gmail.com</a> ou utilizar o formulário de contato disponível na <a href="{{ route('contato') }}">página de contato</a>.</p>
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
