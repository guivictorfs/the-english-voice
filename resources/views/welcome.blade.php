<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The English Voice - Página Inicial</title>
    @vite('resources/css/welcome.css') <!-- Vite para carregar o CSS compilado -->
</head>

<body>
    <!-- Main Content -->
    <div class="container text-center my-5">
        <h1 class="display-2 mb-4">Bem-vindo ao <span id="typing-effect"></span></h1>
        <p class="lead mb-5">Seu portal para o aprendizado de línguas!</p>

        <!-- Cards para Entrar e Criar Conta -->
        <div class="row g-4">
            <!-- Card de Entrar -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-3"
                    style="cursor: pointer; transition: transform 0.3s ease;">
                    <div class="card-body p-4">
                        <h5 class="card-title text-primary fs-4">Entrar</h5>
                        <p class="card-text text-muted">Acesse sua conta para gerenciar seus artigos, mensagens e mais.
                        </p>
                    </div>
                    <div class="card-footer text-center bg-transparent border-0">
                        <i class="bi bi-box-arrow-in-right fs-2 text-primary"></i>
                    </div>
                </div>
                <!-- Link para redefinir senha -->
                <a href="{{ route('password.request') }}" class="forgot-password text-danger">Esqueceu a senha?</a>
            </div>

            <!-- Card de Criar Conta -->
            <div class="col-md-6">
                <a href="{{ route('register') }}" class="text-decoration-none">
                    <div class="card shadow-lg border-0 rounded-3 card-criar-conta"
                        style="cursor: pointer; transition: transform 0.3s ease;">
                        <div class="card-body p-4">
                            <h5 class="card-title text-success fs-4">Criar Conta</h5>
                            <p class="card-text text-muted">Cadastre-se para começar a compartilhar seus conhecimentos.
                            </p>
                        </div>
                        <div class="card-footer text-center bg-transparent border-0">
                            <i class="bi bi-person-plus fs-2 text-success"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <div class="container my-5">
        <h2 class="mb-4">Por que usar o The English Voice?</h2>
        <div class="row text-center">
            <div class="col-md-4 hover-effect">
                <i class="bi bi-book fs-2 text-primary"></i>
                <h5 class="mt-3">Artigos Educativos</h5>
                <p class="text-muted">Explore artigos escritos por alunos e revisados por professores experientes.</p>
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
    <div class="container my-5">
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
    <div class="container my-5 pb-5">
        <h2 class="mb-4">O que nossos usuários dizem</h2>
        <div class="row">
            <div class="col-md-6 hover-effect">
                <blockquote class="blockquote">
                    <p class="mb-0">"O The English Voice me ajudou a melhorar meu inglês enquanto colaborava com outros
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
    <!-- Footer -->
    <footer class="footer bg-light py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        </div>
    </footer>

    <!-- Scripts -->
    @vite('resources/js/app.js') <!-- Vite para carregar o JavaScript compilado -->
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