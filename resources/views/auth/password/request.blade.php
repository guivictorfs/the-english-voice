<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Senha - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/password-request.css')
</head>

<body>
    <div class="container">
        <div class="text-center">
            <img src="{{ asset('/img/logo-cps.png') }}" alt="The English Voice" class="img-fluid" style="max-width: 50em;">
        </div>
        <h1 class="text-center mb-4 mt-4">
            <i class="fas fa-lock-open text-primary"></i> Recuperar Senha
        </h1>

        <div class="row justify-content-center d-flex align-items-center">
            <!-- Coluna da Imagem -->
            <div class="col-md-4 text-center me-5">
                <img src="{{ asset('img/forgot-password.svg') }}" alt="Recuperar senha" class="img-fluid" style="max-width: 100%;">
            </div>

            <!-- Coluna do Formulário -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('password.email') }}" class="p-4 shadow rounded">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Botão de Recuperar -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-envelope me-2"></i>Enviar Link de Recuperação
                        </button>
                    </div>

                    <!-- Links para início e login -->
                    <div class="mt-4 text-center d-flex justify-content-center gap-2">
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary"><i class="fas fa-home me-1"></i> Início</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Mensagem de Sucesso -->
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Mensagem de Erro -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>