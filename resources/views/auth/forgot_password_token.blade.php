<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha (Token Exibido) - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3 text-center">Redefinir Senha</h4>
                <form method="POST" action="{{ route('password.token.link') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail cadastrado</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Gerar link de redefinição</button>
                </form>
                @if(session('reset_link'))
                    <div class="alert alert-success mt-3">
                        <strong>Link de redefinição:</strong><br>
                        <a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a>
                        <br><small>Copie e cole este link no navegador para redefinir sua senha.</small>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
</body>
</html>
