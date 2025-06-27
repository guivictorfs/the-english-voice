<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    {{-- @vite('resources/js/app.js') Removido para evitar erro de JS quebrado no dashboard --}}
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="#">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @php
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Str;
    @endphp

    <!-- Feedback visual de sucesso/erro -->
    <div class="container mt-4">
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
    </div>

    <!-- Conteúdo principal -->
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-2 mt-3"><i class="fas fa-book-open"></i> Artigos Postados</h2>

<!-- Tags selecionadas (acima da lista) -->
@php
    $selectedTags = request('tags');
    if (is_string($selectedTags)) {
        $selectedTags = [$selectedTags];
    }
@endphp
@if(request('tag') || request('author'))
    <div class="mb-3 d-flex align-items-center">
        @if(request('tag'))
            <span class="me-2 text-secondary"><i class="fas fa-filter"></i> Tag filtrada:</span>
            <span class="badge bg-primary text-light me-2" style="font-size:1em;">
                {{ request('tag') }}
            </span>
        @endif
        @if(request('author'))
            <span class="me-2 text-secondary"><i class="fas fa-user"></i> Autor filtrado:</span>
            <span class="badge bg-success text-light me-2" style="font-size:1em;">
                {{ request('author') }}
            </span>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn-outline-danger btn-sm ms-2" title="Limpar filtro">
            <i class="fas fa-times me-1"></i> Limpar filtro
        </a>
    </div>
@endif
<!-- Botão para abrir modal de filtro -->
<div class="mb-4">
    <button class="btn btn-outline-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalFiltroTags">
        <i class="fas fa-tags me-2"></i> Filtrar
    </button>
</div>
<!-- Modal de filtro de tags -->
<div class="modal fade" id="modalFiltroTags" tabindex="-1" aria-labelledby="modalFiltroTagsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFiltroTagsLabel"><i class="fas fa-tags me-2 text-primary"></i>Filtrar artigos por tags</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form method="GET" action="{{ route('dashboard') }}">
        <div class="modal-body">
          <div class="mb-3">
            <label for="filtro-tag" class="form-label">Filtrar por palavra-chave (tag):</label>
            <select id="filtro-tag" name="tag" class="form-select w-100">
                <option value="">Selecione uma palavra-chave...</option>
                @foreach(App\Models\Keyword::orderBy('name')->get() as $keyword)
                    <option value="{{ $keyword->name }}" {{ request('tag') == $keyword->name ? 'selected' : '' }}>{{ $keyword->name }}</option>
                @endforeach
            </select>
          </div>
          <div>
            <label for="filtro-autor" class="form-label">Filtrar por autor:</label>
            <select id="filtro-autor" name="author" class="form-select w-100">
                <option value="">Selecione um autor...</option>
                @foreach(App\Models\User::orderBy('name')->get() as $user)
                    <option value="{{ $user->name }}" {{ request('author') == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-filter me-1"></i> Aplicar filtro
          </button>
          @if(request('tags'))
            <a href="{{ route('dashboard') }}" class="btn btn-outline-danger" title="Remover filtro de tags">
              <i class="fas fa-times me-1"></i> Limpar filtro
            </a>
          @endif
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#dashboard-tags').select2({
        placeholder: 'Filtrar por tag...',
        allowClear: true,
        width: '100%'
    });
});
// Exibe campo para motivo personalizado ao selecionar 'Outro' no modal denúncia
document.addEventListener('DOMContentLoaded', function() {
    // Habilita/desabilita botão de submit do modal denúncia
    $(document).on('input change', 'select[id^="motivo-"]', function() {
        var id = $(this).attr('id').replace('motivo-', '');
        var motivo = $('#motivo-' + id).val();
        var btn = $('#btnEnviarDenuncia-' + id);
        if (motivo && motivo !== '') {
            btn.prop('disabled', false);
        } else {
            btn.prop('disabled', true);
        }
    });

    // Ao abrir o modal, dispara evento change manualmente para garantir estado correto do botão
    $(document).on('shown.bs.modal', '.modal', function () {
        var modal = $(this);
        var motivoSelect = modal.find('select[id^="motivo-"]');
        motivoSelect.trigger('change');
    });

    // Validação extra no submit do formulário de denúncia
    $(document).on('submit', 'form[action*="artigos/denunciar"]', function(e) {
        var form = $(this);
        var motivo = form.find('select[name="motivo"]').val();
        var outroMotivo = form.find('textarea[name="outro_motivo"]');
        if(motivo === 'Outro') {
            if(!outroMotivo.val() || !outroMotivo.val().trim()) {
                alert('Por favor, descreva o motivo da denúncia.');
                outroMotivo.focus();
                e.preventDefault();
                form.find('button[type="submit"]').prop('disabled', false);
                return false;
            }
        }
    });
});
</script>
@endsection

                @if($articles->count())
                    <div>
                        @foreach($articles as $article)
                            <div class="artigo-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1">{{ $article->title }}</h5>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDenuncia-{{ $article->article_id }}" title="Denunciar artigo">
    <i class="fas fa-flag"></i> Denunciar
</button>
@if(isset($article->denuncias) && $article->denuncias > 0)
    <span class="badge bg-warning text-dark ms-2">{{ $article->denuncias }} denúncia{{ $article->denuncias > 1 ? 's' : '' }}</span>
@endif

<!-- Modal Denúncia -->
<div class="modal fade" id="modalDenuncia-{{ $article->article_id }}" tabindex="-1" aria-labelledby="modalDenunciaLabel-{{ $article->article_id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('artigos.denunciar', $article->article_id) }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalDenunciaLabel-{{ $article->article_id }}"><i class="fas fa-flag text-danger me-2"></i>Motivo da denúncia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="motivo-{{ $article->article_id }}" class="form-label">Selecione o motivo:</label>
            <select class="form-select" name="motivo" id="motivo-{{ $article->article_id }}" required>
              <option value="">Escolha...</option>
              <option value="Palavra inadequada">Palavra inadequada</option>
              <option value="Conteúdo ofensivo">Conteúdo ofensivo</option>
              <option value="Plágio">Plágio</option>
              <option value="Outro">Outro</option>
            </select>
          </div>
          <div class="mb-3" id="outroMotivoDiv-{{ $article->article_id }}">
    <label for="outroMotivo-{{ $article->article_id }}" class="form-label">Descreva o motivo (opcional):</label>
    <textarea class="form-control" name="outro_motivo" id="outroMotivo-{{ $article->article_id }}" rows="3"></textarea>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger" id="btnEnviarDenuncia-{{ $article->article_id }}">Enviar denúncia</button>
        </div>
      </form>
    </div>
  </div>
</div>
                                </div>
                                <p class="mb-1 text-muted">
                                    Por
                                    @if($article->authors && $article->authors->count())
                                        @foreach($article->authors as $i => $author)
                                            <a href="{{ route('dashboard', ['author' => $author->name]) }}" class="text-decoration-none fw-bold text-success" title="Filtrar por {{ $author->name }}">{{ $author->name }}</a>@if($i < $article->authors->count() - 1), @endif
                                        @endforeach
                                    @else
                                        Autor desconhecido
                                    @endif
                                    em {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                </p>


                                {{-- Exibe conteúdo ou PDF --}}
                                @if(isset($article->content) && $article->content !== null && trim($article->content) !== '')
                                    <div class="mb-2 mt-2 text-start">{!! Str::limit($article->content, 400) !!}</div>
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

                                @if($article->keywords && $article->keywords->count())
                                    <div class="mt-2">
                                        @foreach($article->keywords as $kw)
                                            <a href="{{ route('dashboard', ['tag' => $kw->name]) }}" class="badge bg-info text-dark me-1 text-decoration-none" title="Filtrar pela tag '{{ $kw->name }}'">{{ $kw->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Nenhum texto postado ainda.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} The English Voice. Todos os direitos reservados.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dashboard-tags').select2({
                placeholder: 'Filtrar por tag...',
                allowClear: true,
                width: '100%'
            });
        });
        if (typeof bootstrap === 'undefined') {
            alert('Bootstrap JS NÃO carregado!');
        }
    </script>
</body>
</html>
