<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Tags/Keywords - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('keywords.index') }}">Tags</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-tags"></i> Gerenciar Tags/Keywords</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('keywords.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="Adicionar nova tag..." required>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>
        </form>

        @if ($keywords->count())
            <ul class="list-group">
                @foreach ($keywords as $keyword)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $keyword->name }}
                        <form action="{{ route('keywords.destroy', $keyword->keyword_id) }}" method="POST" onsubmit="return confirm('Deseja remover esta tag?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remover</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info mt-4">Nenhuma tag cadastrada ainda.</div>
        @endif
    </div>
</body>
</html>
