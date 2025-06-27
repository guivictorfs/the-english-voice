<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
@vite('resources/css/help.css')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link" href="/">Início</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="/dashboard">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="/sobre">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="/help">Ajuda</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="/contato">Contato</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="help-header">
        <h1>Precisa de Ajuda?</h1>
        <p>Encontre respostas rápidas para as dúvidas mais comuns ou entre em contato com nosso suporte.</p>
    </div>
    <div class="help-section">
        <div class="help-card">
    <span class="icon"><i class="fas fa-upload"></i></span>
    <h3>Como enviar artigos?</h3>
    <p>Acesse a página de <a href="{{ route('artigos.postar') }}" class="help-link">escrever artigos</a>, escolha se deseja escrever ou fazer upload de um arquivo, preencha os dados e faça upload do seu arquivo.</p>
    </div>
<div class="help-card">
    <span class="icon"><i class="fas fa-star"></i></span>
    <h3>Como funciona a avaliação?</h3>
    <p>Cada artigo enviado é avaliado pelos estudantes, que atribuem uma nota em estrelas. Você será notificado sobre o resultado no painel.</p>
</div>
<div class="help-card">
    <span class="icon"><i class="fas fa-user-check"></i></span>
    <h3>Minha conta e perfil</h3>
    <p>No menu de perfil, você pode atualizar seus dados pessoais, trocar a senha e acompanhar o histórico de artigos enviados e avaliados.</p>
</div>
<div class="help-card">
    <span class="icon"><i class="fas fa-headset"></i></span>
    <h3>Fale com o suporte</h3>
    <p>Se tiver dúvidas, dificuldades ou sugestões, envie uma mensagem pelo formulário de contato ou utilize o e-mail de suporte informado abaixo.</p>
</div>
    </div>
    <div class="help-links">
        <a href="/">Voltar ao início</a>
        <a href="/contato">Fale conosco</a>
    </div>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>