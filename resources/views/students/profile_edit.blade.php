@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-4">
    <h2 class="mb-4 pt-4 fs-2">Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fs-4">Foto de Perfil</label>
            @if($user->profile_photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Foto atual" class="img-thumbnail" style="max-width:120px;">
                </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small class="form-text text-muted">JPG ou PNG até 2 MB.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">RA</label>
            <input type="text" class="form-control" value="{{ $user->ra }}" disabled>
            <small class="form-text text-muted">Para alterar seu RA, entre em contato com o administrador.</small>

        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Curso</label>
            <input type="text" class="form-control" value="{{ $courses[$user->course_id] ?? '' }}" disabled>
            <small class="form-text text-muted">Para alterar curso, entre em contato com o administrador.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Perfil</label>
            <input type="text" class="form-control" value="{{ $user->role }}" disabled>
            <small class="form-text text-muted">Para alterar o tipo de perfil, entre em contato com o administrador.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fs-4">Conta criada em</label>
            <input type="text" class="form-control" value="{{ $user->created_at->format('d/m/Y H:i') }}" disabled>
        </div>

        <hr>
        <h5 class="fs-4">Alterar Senha</h5>
        <div class="mb-3">
            <label class="form-label fs-5">Nova Senha</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Digite a nova senha">
            <!-- Medidor de força -->
            <div id="password-strength-container" class="d-none">
                <div class="progress mt-2">
                    <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small id="password-strength-text" class="form-text text-muted"></small>
                <ul class="mt-2">
                    <li id="length" class="text-danger">Mínimo 8 caracteres</li>
                    <li id="lowercase" class="text-danger">Letra minúscula</li>
                    <li id="uppercase" class="text-danger">Letra maiúscula</li>
                    <li id="number" class="text-danger">Número</li>
                    <li id="special" class="text-danger">Caractere especial</li>
                </ul>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fs-5">Confirmar Nova Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme a nova senha">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('students.account') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

@push('scripts')
<script>
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('password_confirmation');
const strengthMeter = document.getElementById('password-strength-meter');
const strengthText = document.getElementById('password-strength-text');
const passwordRequirements = {
    length: document.getElementById('length'),
    lowercase: document.getElementById('lowercase'),
    uppercase: document.getElementById('uppercase'),
    number: document.getElementById('number'),
    special: document.getElementById('special')
};

const updateStrengthIndicator = (password) => {
    const strength = [/[a-z]/, /[A-Z]/, /[0-9]/, /[^A-Za-z0-9]/].reduce((acc, regex) => acc + regex.test(password), 0);
    const width = Math.min(100, (strength + (password.length >= 8 ? 1 : 0)) * 20);
    strengthMeter.style.width = width + '%';
    strengthMeter.className = 'progress-bar';
    if (width < 40) strengthMeter.classList.add('bg-danger');
    else if (width < 80) strengthMeter.classList.add('bg-warning');
    else strengthMeter.classList.add('bg-success');
    strengthText.textContent = width < 40 ? 'Senha fraca' : (width < 80 ? 'Média' : 'Forte');
};

const validatePassword = (password) => {
    const conditions = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };
    for (const [key, value] of Object.entries(conditions)) {
        passwordRequirements[key].classList.toggle('text-success', value);
        passwordRequirements[key].classList.toggle('text-danger', !value);
    }
    return Object.values(conditions).every(Boolean);
};

passwordField.addEventListener('input', () => {
    const pwd = passwordField.value;
    document.getElementById('password-strength-container').classList.toggle('d-none', !pwd);
    validatePassword(pwd);
    updateStrengthIndicator(pwd);
});

confirmPasswordField.addEventListener('input', () => {
    const match = confirmPasswordField.value === passwordField.value;
    confirmPasswordField.classList.toggle('is-invalid', !match);
    confirmPasswordField.classList.toggle('is-valid', match);
});
</script>
@endpush
@endsection
