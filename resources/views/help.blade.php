<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('resources/css/welcome.css') }}" rel="stylesheet">
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
                        <a class="nav-link" href="#">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="/help">Ajuda</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center text-primary"><i class="fas fa-question-circle"></i> Central de Ajuda</h2>
                    <p class="lead text-center">Precisa de suporte? Veja abaixo como podemos ajudar!</p>
                    <hr>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><i class="fas fa-user-plus text-success"></i> <strong>Cadastro:</strong> Se você está com dificuldades para criar sua conta, confira se todos os campos obrigatórios estão preenchidos corretamente. Professores/Admins precisam de código de verificação.</li>
                        <li class="list-group-item"><i class="fas fa-sign-in-alt text-primary"></i> <strong>Login:</strong> Certifique-se de usar o e-mail e senha cadastrados. Se esqueceu a senha, utilize o link de recuperação.</li>
                        <li class="list-group-item"><i class="fas fa-key text-warning"></i> <strong>Recuperação de Senha:</strong> Informe seu e-mail e siga as instruções enviadas para redefinir sua senha.</li>
                        <li class="list-group-item"><i class="fas fa-envelope text-info"></i> <strong>Não recebeu o e-mail?</strong> Verifique a caixa de spam/lixo eletrônico. Se o problema persistir, entre em contato pelo e-mail abaixo.</li>
                    </ul>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-envelope"></i> Suporte: <a href="mailto:englishvoice@fatec.sp.gov.br">englishvoice@fatec.sp.gov.br</a>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/" class="btn btn-outline-secondary"><i class="fas fa-home"></i> Voltar ao início</a>
                        <a href="{{ route('login') }}" class="btn btn-primary ms-2"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="{{ route('register') }}" class="btn btn-success ms-2"><i class="fas fa-user-plus"></i> Criar Conta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer bg-light py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('resources/css/welcome.css') }}" rel="stylesheet">
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
                        <a class="nav-link" href="#">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="/help">Ajuda</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center text-primary"><i class="fas fa-question-circle"></i> Central de Ajuda</h2>
                    <p class="lead text-center">Precisa de suporte? Veja abaixo como podemos ajudar!</p>
                    <hr>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><i class="fas fa-user-plus text-success"></i> <strong>Cadastro:</strong> Se você está com dificuldades para criar sua conta, confira se todos os campos obrigatórios estão preenchidos corretamente. Professores/Admins precisam de código de verificação.</li>
                        <li class="list-group-item"><i class="fas fa-sign-in-alt text-primary"></i> <strong>Login:</strong> Certifique-se de usar o e-mail e senha cadastrados. Se esqueceu a senha, utilize o link de recuperação.</li>
                        <li class="list-group-item"><i class="fas fa-key text-warning"></i> <strong>Recuperação de Senha:</strong> Informe seu e-mail e siga as instruções enviadas para redefinir sua senha.</li>
                        <li class="list-group-item"><i class="fas fa-envelope text-info"></i> <strong>Não recebeu o e-mail?</strong> Verifique a caixa de spam/lixo eletrônico. Se o problema persistir, entre em contato pelo e-mail abaixo.</li>
                    </ul>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-envelope"></i> Suporte: <a href="mailto:englishvoice@fatec.sp.gov.br">englishvoice@fatec.sp.gov.br</a>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/" class="btn btn-outline-secondary"><i class="fas fa-home"></i> Voltar ao início</a>
                        <a href="{{ route('login') }}" class="btn btn-primary ms-2"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="{{ route('register') }}" class="btn btn-success ms-2"><i class="fas fa-user-plus"></i> Criar Conta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer bg-light py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
