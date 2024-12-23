<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Conta - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/register.css')
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">
            <i class="fas fa-user-plus text-success"></i> Criar Conta
        </h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="{{ route('register') }}" class="p-4 shadow rounded" id="registerForm">
                    @csrf
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="fas fa-user"></i> Nome Completo</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome"
                            required value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- E-mail -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail"
                            required value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Senha</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Digite sua senha" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmar
                            Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirme sua senha" required>
                    </div>
                    <!-- Tipo de Usuário -->
                    <div class="mb-3">
                        <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Você é:</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Selecione</option>
                            <option value="Aluno" {{ old('role') === 'Aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="Professor" {{ old('role') === 'Professor' ? 'selected' : '' }}>Professor
                            </option>
                            <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- RA -->
                    <div class="mb-3" id="ra-container" style="display: none;">
                        <label for="ra" class="form-label"><i class="fas fa-id-badge"></i> RA</label>
                        <input type="text" name="ra" id="ra" class="form-control" placeholder="Digite seu RA"
                            maxlength="20" value="{{ old('ra') }}">
                        @error('ra')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Curso -->
                    <div class="mb-3">
                        <label for="course" class="form-label"><i class="fas fa-graduation-cap"></i> Curso</label>
                        <select name="course" id="course" class="form-select" required>
                            <option value="">Selecione seu curso</option>
                            @foreach (App\Enums\Course::cases() as $course)
                                <option value="{{ $course->value }}" {{ old('course') === $course->value ? 'selected' : '' }}>
                                    {{ $course->value }}
                                </option>
                            @endforeach
                            <option value="Professor" {{ old('course') === 'Professor' ? 'selected' : '' }}>Professor
                            </option>
                            <option value="Administrador" {{ old('course') === 'Administrador' ? 'selected' : '' }}>
                                Administrador</option>
                        </select>
                        @error('course')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Botão -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitButton">
                            <i class="fas fa-check"></i> Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Verificação -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Código de Verificação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="verification_code">Digite o código de verificação:</label>
                    <input type="text" id="verification_code" class="form-control" placeholder="Código de verificação"
                        required>
                    <small id="error-message" class="text-danger" style="display: none;">Código inválido. Tente
                        novamente.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="verifyButton">Verificar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle"></i> Sucesso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Seu cadastro foi realizado com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Erro -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-times-circle"></i> Erro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ocorreu um erro no cadastro. Tente novamente.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
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
</body>
<script>
    let isVerified = false; // Variável para rastrear se a verificação foi concluída

    document.getElementById('role').addEventListener('change', function () {
        const role = this.value;

        if (role === 'Professor' || role === 'Admin') {
            isVerified = false; // Reseta a verificação ao mudar para um tipo protegido
            const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
            modal.show();

            document.getElementById('verifyButton').addEventListener('click', async function () {
                const enteredCode = document.getElementById('verification_code').value;

                const response = await fetch('/validate-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ role, code: enteredCode }),
                });

                const result = await response.json();

                if (result.valid) {
                    isVerified = true; // Define como verdadeiro ao validar o código
                    const modal = bootstrap.Modal.getInstance(document.getElementById('verificationModal'));
                    modal.hide();
                    document.getElementById('error-message').style.display = 'none';
                } else {
                    isVerified = false;
                    document.getElementById('error-message').style.display = 'block';
                }
            });

            document.querySelector('.btn-close, .btn-danger').addEventListener('click', function () {
                // Reseta o campo de seleção ao cancelar
                document.getElementById('role').value = '';
            });
        } else {
            isVerified = true; // Para usuários que não precisam de verificação
            document.getElementById('ra-container').style.display = role === 'Aluno' ? 'block' : 'none';
            if (role !== 'Aluno') {
                document.getElementById('ra').value = ''; // Limpa o RA se não for Aluno
            }
        }
    });

    // Bloqueia o envio do formulário se não estiver verificado
    document.getElementById('registerForm').addEventListener('submit', function (event) {
        if (!isVerified) {
            event.preventDefault();
            alert('Você deve concluir a verificação para continuar.');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>