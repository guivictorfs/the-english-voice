<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Str; use Illuminate\Support\Facades\DB; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Artigos Denunciados - The English Voice</title>
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
                        <a class="nav-link active" href="{{ route('admin.artigos.pendentes') }}">Artigos Pendentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-flag"></i> Artigos Denunciados para Revisão</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if($articles->count())
            <ul class="list-group">
                @foreach($articles as $article)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $article->title }}</strong>
                                <span class="badge bg-warning text-dark ms-2">{{ $article->denuncias }} denúncia{{ $article->denuncias > 1 ? 's' : '' }}</span>
                                <div class="text-muted small">
                                    Por @if($article->authors && $article->authors->count())
                                        {{ $article->authors->pluck('name')->join(', ') }}
                                    @else
                                        Autor desconhecido
                                    @endif
                                    em {{ $article->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('admin.artigos.aprovar', $article->article_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aprovar</button>
                                </form>
                                <form action="{{ route('admin.artigos.excluir', $article->article_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este artigo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Excluir</button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-2">
                            @if(isset($article->content) && $article->content !== null && trim($article->content) !== '')
                                <div class="mb-2">{!! Str::limit($article->content, 400) !!}</div>
                            @else
                                @php
                                    $file = DB::table('file_upload')
                                        ->where('article_id', $article->article_id)
                                        ->orderByDesc('created_at')
                                        ->first();
                                @endphp
                                @if($file)
                                    <div class="mb-2">
                                        <strong>Arquivo PDF:</strong>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Abrir PDF
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted">Nenhum arquivo disponível.</div>
                                @endif
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info">Nenhum artigo pendente de revisão.</div>
        @endif
    </div>
</body>
</html>


@section('content')
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-flag"></i> Artigos Denunciados para Revisão</h2>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if($articles->count())
        <div class="list-group">
            @foreach($articles as $article)
                <div class="list-group-item mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $article->title }}</h5>
                            <span class="badge bg-warning text-dark">{{ $article->denuncias }} denúncia{{ $article->denuncias > 1 ? 's' : '' }}</span>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('admin.artigos.aprovar', $article->article_id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Aprovar"><i class="fas fa-check"></i> Aprovar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.artigos.excluir', $article->article_id) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este artigo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir"><i class="fas fa-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                    <p class="mb-1 text-muted">
                        Por
                        @if($article->authors && $article->authors->count())
                            {{ $article->authors->pluck('name')->join(', ') }}
                        @else
                            Autor desconhecido
                        @endif
                        em {{ $article->created_at->format('d/m/Y H:i') }}
                    </p>
                    @if(isset($article->content) && $article->content !== null && trim($article->content) !== '')
                        <div class="mb-2">{!! Str::limit($article->content, 400) !!}</div>
                    @else
                        @php
                            $file = DB::table('file_upload')
                                ->where('article_id', $article->article_id)
                                ->orderByDesc('created_at')
                                ->first();
                        @endphp
                        @if($file)
                            <div class="mb-2">
                                <strong>Arquivo PDF:</strong>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    Abrir PDF
                                </a>
                            </div>
                            <iframe 
                                src="{{ asset('storage/' . $file->file_path) }}" 
                                width="100%" 
                                height="400px" 
                                style="border:1px solid #ccc;">
                            </iframe>
                        @else
                            <div class="text-muted">Nenhum arquivo disponível.</div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Nenhum artigo pendente de revisão.</div>
    @endif
</div>
@endsection
