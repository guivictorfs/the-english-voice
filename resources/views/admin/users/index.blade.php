@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Usuários cadastrados</h2>

    <form method="GET" class="row g-2 mb-3" action="{{ route('admin.users.index') }}">
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nome ou email" class="form-control">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
        </div>
        @if(request('q'))
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/profile_photos/'.$user->profile_photo) }}" alt="avatar" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-2x text-secondary"></i>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    
                                        <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary me-1">Editar</a>
                                <a href="{{ route('admin.users.logs', $user->id) }}" class="btn btn-sm btn-outline-secondary">Logs</a>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection
