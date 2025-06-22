<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Necessário - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="alert alert-warning text-center mt-5">
            <h4 class="alert-heading">Acesso restrito</h4>
            <p>Você precisa estar logado para acessar o dashboard.</p>
            <hr>
            <a href="{{ route('login') }}" class="btn btn-primary">Fazer login</a>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
</body>
</html>
