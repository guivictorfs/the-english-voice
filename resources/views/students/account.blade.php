<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Artigos - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link active" href="/students/account">Meus Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="/dashboard">Painel Geral</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="/help">Ajuda</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-3">
                @php $photo = Auth::user()->profile_photo; @endphp
                <img src="{{ $photo ? asset('storage/'.$photo) : 'https://via.placeholder.com/60x60?text=Avatar' }}" alt="Foto de Perfil" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                <h2 class="m-0"><i class="fas fa-book"></i> Meus Artigos Postados</h2>
            </div>
            <a href="{{ route('students.profile') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-user"></i> Editar Perfil</a>
        </div>
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
            <div class="list-group">
                @foreach($articles as $article)
                    <div class="list-group-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $article->title }}</strong>
<span class="badge {{ ($article->denuncias ?? 0) > 0 ? 'bg-danger' : 'bg-secondary' }} ms-2" title="Denúncias">
    <i class="fas fa-flag"></i> {{ $article->denuncias ?? 0 }}
</span>
<span class="badge bg-warning text-dark ms-2" title="Média das avaliações">
    <i class="fas fa-star"></i> {{ $article->media_avaliacoes !== null ? $article->media_avaliacoes : '-' }}
</span>
<!-- DEBUG: denuncias={{ $article->denuncias }}, media_nota={{ $article->media_nota }} -->
                            </div>
                            <div>
                                <a href="{{ route('artigos.visualizar', $article->article_id) }}" class="btn btn-sm btn-outline-info me-2" target="_blank" title="Ver Artigo"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('artigos.edit', $article->article_id) }}" class="btn btn-sm btn-outline-primary me-2" title="Editar"><i class="fas fa-edit"></i></a>
                                <!-- Botão que abre o modal de confirmação -->
<button type="button" class="btn btn-sm btn-outline-danger" title="Excluir" data-bs-toggle="modal" data-bs-target="#modalExcluir-{{ $article->article_id }}">
    <i class="fas fa-trash"></i>
</button>
<!-- Modal de confirmação -->
<div class="modal fade" id="modalExcluir-{{ $article->article_id }}" tabindex="-1" aria-labelledby="modalExcluirLabel-{{ $article->article_id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalExcluirLabel-{{ $article->article_id }}">Excluir Artigo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <strong>ATENÇÃO:</strong> Esta ação é <span class="text-danger">permanente e irreversível</span>.<br>
        Tem certeza que deseja excluir este artigo para sempre?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form action="{{ route('artigos.excluir', $article->article_id) }}" method="POST" class="d-inline m-0 p-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Excluir definitivamente</button>
        </form>
      </div>
    </div>
  </div>
</div>
    
                                </form>
                            </div>
                        </div>
                        <div class="text-muted small mt-1">
                            Postado em {{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y H:i') }}
                        </div>
                        @if(!empty($article->feedback))
                        <div class="alert alert-info mt-2 mb-0 p-2">
                            <strong>Feedback:</strong> {{ $article->feedback }}
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-secondary">Você ainda não postou nenhum artigo.</div>
        @endif
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
