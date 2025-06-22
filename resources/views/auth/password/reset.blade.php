<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redefinir Senha - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/password-reset.css')
</head>

<body>
    <div class="container">
        <div class="text-center">
            <img src="{{ asset('/img/logo-cps.png') }}" alt="The English Voice" class="img-fluid" style="max-width: 50em;">
        </div>
        <h1 class="text-center mb-4 mt-4">
            <i class="fas fa-key text-primary"></i> Redefinir Senha
        </h1>

        <div class="row justify-content-center d-flex align-items-center">
            <!-- Coluna da Imagem -->
            <div class="col-md-4 text-center me-5">
                <img src="{{ asset('img/reset-password.svg') }}" alt="Redefinir senha" class="img-fluid" style="max-width: 100%;">
            </div>

            <!-- Coluna do Formulário -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('password.update') }}" class="p-4 shadow rounded">
                    @csrf
                    
                    <!-- Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required value="{{ $email ?? old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Nova Senha</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua nova senha" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme sua nova senha" required>
                    </div>

                    <!-- Botão de Redefinição -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sync-alt me-2"></i>Redefinir Senha
                        </button>
                    </div>

                    <!-- Links Adicionais -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt me-1"></i>Voltar para login
                        </a>
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