<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3 text-center">Redefinir Senha</h4>
                <form id="resetRequestForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail cadastrado</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar link de redefinição</button>
                </form>
                <div id="resetRequestMsg" class="mt-3"></div>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
    <script>
        document.getElementById('resetRequestForm').onsubmit = function(e) {
            e.preventDefault();
            fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({email: document.getElementById('email').value})
            })
            .then(resp => resp.ok ? resp.json() : Promise.reject(resp))
            .then(data => {
                document.getElementById('resetRequestMsg').innerHTML = '<div class="alert alert-success">Se o e-mail existir, um link de redefinição foi enviado.</div>';
            })
            .catch(err => {
                document.getElementById('resetRequestMsg').innerHTML = '<div class="alert alert-danger">Erro ao solicitar redefinição. Tente novamente.</div>';
            });
        };
    </script>
</body>
</html>
