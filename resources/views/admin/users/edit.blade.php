@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h3 class="mb-4">Editar Usuário {{ $user->name }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="Aluno"   {{ old('role', $user->role)=='Aluno' ? 'selected' : '' }}>Aluno</option>
                <option value="Professor" {{ old('role', $user->role)=='Professor' ? 'selected' : '' }}>Professor</option>
                <option value="Admin"   {{ old('role', $user->role)=='Admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">RA</label>
            <input type="text" name="ra" class="form-control @error('ra') is-invalid @enderror" value="{{ old('ra', $user->ra) }}">
            @error('ra') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Curso</label>
            <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
                <option value="">-- selecione --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->course_id }}" {{ old('course_id', $user->course_id)==$course->course_id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                @endforeach
            </select>
            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@endsection
