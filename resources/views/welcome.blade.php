<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The English Voice - Página Inicial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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


    <!-- Main Content -->
    <div class="container mb-3">
        <div class="container container-title text-center my-3">
            <h1 class="display-2 mb-4">Bem-vindo ao <br><span id="typing-effect"></span></h1>
            <p class="lead mb-5">Seu portal para o aprendizado de línguas!</p>
        </div>
        <div class="text-center pb-2">
            <img src="{{ asset('/IMG/happy-announcement.svg') }}" alt="Acessar conta" class="img-fluid img-svg">
        </div>
        <hr class="my-5">
        <div class="container text-center my-5">
            <!-- Cards para Entrar e Criar Conta -->
            <div class="row g-4">
                <!-- Card de Entrar -->
                <div class="col-md-6">
                    <div class="card shadow-lg border-0 rounded-3"
                        style="cursor: pointer; transition: transform 0.3s ease;">
                        <div class="card-body p-4">
                            <h5 class="card-title text-primary fs-4">Entrar</h5>
                            <p class="card-text text-muted">Acesse sua conta para gerenciar seus artigos, mensagens e
                                mais.
                            </p>
                        </div>
                        <div class="card-footer text-center bg-transparent border-0">
                            <i class="bi bi-box-arrow-in-right fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Card de Criar Conta -->
                <div class="col-md-6">
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        <div class="card shadow-lg border-0 rounded-3 card-criar-conta"
                            style="cursor: pointer; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <h5 class="card-title text-success fs-4">Criar Conta</h5>
                                <p class="card-text text-muted">Cadastre-se para começar a compartilhar seus
                                    conhecimentos.
                                </p>
                            </div>
                            <div class="card-footer text-center bg-transparent border-0">
                                <i class="bi bi-person-plus fs-2 text-success"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card de Esqueceu a Senha -->
                <div class="col-md-6">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <div class="card shadow-lg border-0 rounded-3 card-esqueceu-senha"
                            style="cursor: pointer; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <h5 class="card-title text-danger fs-4">Esqueceu a Senha?</h5>
                                <p class="card-text text-muted">Redefina sua senha de forma fácil e segura.</p>
                            </div>
                            <div class="card-footer text-center bg-transparent border-0">
                                <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card de Ajuda -->
                <div class="col-md-6">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        <div class="card shadow-lg border-0 rounded-3 card-ajuda"
                            style="cursor: pointer; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <h5 class="card-title text-warning fs-4">Ajuda</h5>
                                <p class="card-text text-muted">Precisa de suporte? Entre em contato conosco.</p>
                            </div>
                            <div class="card-footer text-center bg-transparent border-0">
                                <i class="bi bi-question-circle fs-2 text-warning"></i>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
        <hr class="my-5">
        <div class="container my-3">
            <h2 class="mb-4">Por que usar o The English Voice?</h2>
            <div class="row text-center">
                <div class="col-md-4 hover-effect">
                    <i class="bi bi-book fs-2 text-primary"></i>
                    <h5 class="mt-3">Artigos Educativos</h5>
                    <p class="text-muted">Explore artigos escritos por alunos e revisados por professores.</p>
                </div>
                <div class="col-md-4 hover-effect">
                    <i class="bi bi-people fs-2 text-success"></i>
                    <h5 class="mt-3">Comunidade Colaborativa</h5>
                    <p class="text-muted">Conecte-se com outros alunos para compartilhar ideias e experiências.</p>
                </div>
                <div class="col-md-4 hover-effect">
                    <i class="bi bi-trophy fs-2 text-warning"></i>
                    <h5 class="mt-3">Reconhecimento</h5>
                    <p class="text-muted">Receba feedbacks e conquiste destaque com seus melhores artigos.</p>
                </div>
            </div>
        </div>
        <div class="text-center pb-2">
            <img src="{{ asset('/IMG/team-up.svg') }}" alt="Acessar conta" class="img-fluid img-svg">
        </div>
        <hr class="my-5">

        <div class="container my-3">
            <h2 class="mb-4">Funcionalidades</h2>
            <div class="row text-center">
                <div class="col-md-3 hover-effect">
                    <i class="bi bi-upload fs-2 text-info"></i>
                    <h5 class="mt-2">Envie Artigos</h5>
                </div>
                <div class="col-md-3 hover-effect">
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                    <h5 class="mt-2">Aprovação Fácil</h5>
                </div>
                <div class="col-md-3 hover-effect">
                    <i class="bi bi-bar-chart fs-2 text-warning"></i>
                    <h5 class="mt-2">Acompanhe Seu Progresso</h5>
                </div>
                <div class="col-md-3 hover-effect">
                    <i class="bi bi-chat-dots fs-2 text-secondary"></i>
                    <h5 class="mt-2">Receba Feedbacks</h5>
                </div>
            </div>
        </div>
        <div class="text-center pb-2">
            <img src="{{ asset('/IMG/computer.svg') }}" alt="Acessar conta" class="img-fluid img-svg">
        </div>
        <hr class="my-5">

        <div class="container my-3 pb-2">
            <h2 class="mb-4">O que nossos usuários dizem</h2>
            <div class="row">
                <div class="col-md-6 hover-effect">
                    <blockquote class="blockquote">
                        <p class="mb-0">"O The English Voice me ajudou a melhorar meu inglês enquanto colaborava com
                            outros
                            alunos!"</p>
                        <footer class="blockquote-footer mt-1">João Silva, aluno de ADS</footer>
                    </blockquote>
                </div>
                <div class="col-md-6 hover-effect">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Adoro revisar artigos e ver o crescimento dos estudantes no aprendizado de
                            idiomas."</p>
                        <footer class="blockquote-footer mt-1">Profª Taciana Coelho</footer>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="text-center pb-2">
            <img src="{{ asset('/IMG/personal-opinions.svg') }}" alt="Acessar conta" class="img-fluid img-svg">
        </div>
        <hr>

        <div class="text-center pb-4">
            <img src="{{ asset('/IMG/logo-cps.png') }}" alt="Logotipo Centro Paula Souze" class="img-fluid img-logo">
        </div>
    </div>
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



</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const text = "The English Voice";
        const element = document.getElementById("typing-effect");
        let index = 0;
        let isDeleting = false;

        function typeEffect() {
            if (isDeleting) {
                element.textContent = text.substring(0, index);
                index--;
            } else {
                element.textContent = text.substring(0, index + 1);
                index++;
            }

            if (!isDeleting && index === text.length) {
                setTimeout(() => (isDeleting = true), 1000); // Pausa após digitar
            } else if (isDeleting && index === 0) {
                isDeleting = false;
            }

            const speed = isDeleting ? 100 : 150; // Velocidade ao apagar é mais rápida
            setTimeout(typeEffect, speed);
        }

        typeEffect();
    });
</script>


</html>