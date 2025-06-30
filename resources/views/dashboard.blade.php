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
@php use Illuminate\Support\Facades\Auth; @endphp
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
    @php $role = strtolower(Auth::user()->role ?? ''); @endphp
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
    @if($role === 'admin')
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.panel') }}">Painel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.artigos.pendentes') }}">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/courses') }}">Cursos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('keywords.index') }}">Tags</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('forbidden_words.index') }}">Palavras Proibidas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logs.index') }}">Logs</a>
            </li>
        </ul>
    @else
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item underline">
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('students.account') }}">Meus Artigos</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('artigos.postar') }}">Postar Artigo</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('articles.favorites') }}">Favoritos</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('help') }}">Ajuda</a>
            </li>
            <li class="nav-item underline">
                <a class="nav-link" href="{{ route('students.profile') }}">Conta</a>
            </li>
        </ul>
    @endif
    <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
            <form class="d-flex align-items-center" method="GET" action="{{ route('dashboard') }}" style="gap:0.25rem;">
                <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Pesquisar artigos..." aria-label="Pesquisar" value="{{ request('q') }}" style="min-width: 180px;">
                <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                @if(request('q'))
                    @php
                        $query = request()->except('q');
                        $url = route('dashboard') . ($query ? ('?' . http_build_query($query)) : '');
                    @endphp
                    <a href="{{ $url }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpar pesquisa"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </li>
        <li class="nav-item">
            <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
        </li>
    </ul>
            </div>
        </div>
    </nav>

    @php
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Str;
    @endphp



    <!-- Conteúdo principal -->
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center pt-4">
            <div class="col-lg-10 col-xl-8">
                <h2 class="mb-4 mt-3"><i class="fas fa-book-open"></i> Artigos Postados</h2>

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
<!-- Botões de ação -->
<div class="mb-4 d-flex justify-content-start gap-2">
    <a href="{{ route('artigos.melhores') }}" class="btn btn-outline-warning" title="Ver ranking de melhores artigos">
        <i class="fas fa-trophy me-1"></i> Ver Ranking
    </a>
    <button class="btn btn-outline-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalFiltroTags">
        <i class="fas fa-filter me-2"></i> Filtrar
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
    //* Adicionei um estilo para o botão de favorito */
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

                @php
                    $highlight = request('q');
                    if (!function_exists('highlight')) {
                        function highlight($text, $term) {
                            if (!$term || !is_string($term)) return $text;
                            return preg_replace('/(' . preg_quote($term, '/') . ')/i', '<mark>$1</mark>', e($text));
                        }
                    }
                @endphp
                @if($articles->count())
                    <div>
                        @foreach($articles as $article)
    <div class="artigo-card p-3">
    <!-- Feedback visual de sucesso/erro para favoritos -->
    @if(session('success') && session('fav_article_id') == $article->article_id)
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
    @if(session('error') && session('fav_article_id') == $article->article_id)
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
    <!-- 1. Título -->
    @php 
        $mainAuthor = $article->authors->first(); 
        $avatar = $mainAuthor && $mainAuthor->profile_photo ? asset('storage/'.$mainAuthor->profile_photo) : 'https://via.placeholder.com/50x50?text=Avatar';
        $isFavorited = auth()->user()->favorites->contains($article->article_id);
    @endphp
<div class="mb-2 d-flex justify-content-between align-items-center">
    <img src="{{ $avatar }}" alt="Avatar" class="rounded-circle me-3 d-none d-md-block" style="width:50px;height:50px;object-fit:cover;">
    <span class="display-6 fw-bold text-center d-block w-100 text-break" style="font-size:2rem;">{!! highlight($article->title, $highlight) !!}</span>
    @php
        $isAuthor = $article->authors->contains('id', auth()->id());
        $isProfessorOrAdmin = in_array(auth()->user()->role, ['Professor', 'Admin']);
    @endphp
    <div class="ms-2 d-flex align-items-center gap-1">
        <!-- Botão Visualizar Artigo -->
        <a href="{{ route('artigos.visualizar', $article->article_id) }}" class="btn btn-sm btn-outline-info me-1" target="_blank" title="Visualizar artigo">
            <i class="fas fa-eye"></i>
        </a>
        <!-- Botão de Favoritar -->
        @if($isFavorited)
    <form action="{{ route('articles.unfavorite', $article->article_id) }}" method="POST" class="form-favorito d-inline" data-artigo="{{ $article->article_id }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-warning px-2 py-1 favorito-btn" title="Remover dos favoritos">
            <i class="fas fa-star text-warning"></i>
        </button>
    </form>
@else
    <form action="{{ route('articles.favorite', $article->article_id) }}" method="POST" class="form-favorito d-inline" data-artigo="{{ $article->article_id }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-warning px-2 py-1 favorito-btn" title="Salvar nos favoritos">
            <i class="far fa-star"></i>
        </button>
    </form>
@endif
        <!-- Botão de Editar -->
        @if($isAuthor || $isProfessorOrAdmin)
            <a href="{{ route('artigos.edit', $article->article_id) }}" class="btn btn-sm btn-outline-primary ms-1" title="Editar artigo">
                <i class="fas fa-edit"></i> Editar
            </a>
        @endif
    </div>
</div>
    <hr class="my-2">
    <!-- 2. Metadados -->
    <div class="mb-2 text-muted" style="font-size: 1rem;">
        Por
        @if($article->authors && $article->authors->count())
            @foreach($article->authors as $i => $author)
                <a href="{{ route('dashboard', ['author' => $author->name]) }}" class="text-decoration-none fw-bold text-success" title="Filtrar por {{ $author->name }}">{{ $author->name }}</a>@if($i < $article->authors->count() - 1), @endif
            @endforeach
        @else
            Autor desconhecido
        @endif
        em {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}, às {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('H:i') }}
    </div>
    <hr class="my-2">
    @if($article->content && trim($article->content) !== '')
        <div class="mb-3 text-break overflow-hidden text-truncate text-start ps-3 pe-3" style="max-height: 5.5rem; white-space: pre-line;">{!! highlight(Str::limit(strip_tags($article->content), 400), $highlight) !!}</div>
    @else
        @php
            $file = DB::table('file_upload')
                ->where('article_id', $article->article_id)
                ->orderByDesc('created_at')
                ->first();
        @endphp
        @if($file)
            <div class="mb-2 ps-3 pe-3">
                <strong>Arquivo PDF:</strong>
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger ms-2">Abrir PDF</a>
            </div>
            <iframe src="{{ asset('storage/' . $file->file_path) }}" width="100%" height="400px" style="border:1px solid #ccc;"></iframe>
        @else
            <div class="text-muted ps-3 pe-3">Nenhum conteúdo disponível.</div>
        @endif
    @endif

    <hr class="my-2">
    <!-- 4. Avaliação -->
    <div class="mb-2 ps-3 pe-3">
        <x-avaliacao-estrelas :artigo="$article" />
    </div>
    <hr class="my-2">
    <!-- 5. Bloco de denúncia -->
    <div class="denuncia-bloco mb-2">
        <x-ja-denunciado :article-id="$article->article_id" />
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

<hr class="my-2">
<a href="{{ route('artigos.pdf', $article->article_id) }}" class="btn btn-sm btn-outline-danger mt-2" target="_blank" title="Baixar PDF do artigo">
    <i class="fas fa-file-pdf"></i> Baixar PDF
</a>
<hr class="my-2">
@if($article->keywords && $article->keywords->count())
    <div class="pt-2">
        <span class="text-secondary fw-bold me-2" style="letter-spacing:1px;">Tags:</span>
        @foreach($article->keywords as $kw)
            <a href="{{ route('dashboard', ['tag' => $kw->name]) }}" class="badge bg-info text-dark me-1 text-decoration-none" title="Filtrar pela tag '{{ $kw->name }}'">{{ $kw->name }}</a>
        @endforeach
    </div>
@endif
                            </div>
                        @endforeach
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-auto">
                            <div class="card border-0 p-2 bg-light" style="min-width:320px;">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="small text-muted mb-1">
                                        Mostrando <b>{{ $articles->firstItem() }}</b> a <b>{{ $articles->lastItem() }}</b> de <b>{{ $articles->total() }}</b> resultados
                                    </div>
                                    <div>
                                        {{ $articles->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        @if(request('q'))
                            <i class="fas fa-search me-2"></i>Nenhum artigo encontrado para <strong>"{{ request('q') }}"</strong>.
                        @else
                            Nenhum artigo encontrado.
                        @endif
                    </div>
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
<script>
$(document).ready(function() {
    $('.form-avaliacao').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var artigoId = $form.data('artigo');
        var url = $form.attr('action');
        var data = $form.serialize();

        $form.find('button[type=submit]').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                $form.find('.alert').remove();
                $form.append(
                    '<div class="alert alert-success mt-3 mb-0 py-2 px-3" role="alert">' +
                    '<i class="fas fa-check-circle me-2"></i> ' + response.message +
                    '</div>'
                );
                if(response.media !== undefined && response.total !== undefined) {
                    $form.closest('.card-body').find('.fw-bold').html(
                        '<i class="fas fa-star text-warning"></i> Nota média: ' + response.media + '/5'
                    );
                    $form.closest('.card-body').find('.text-muted').text(
                        '(' + response.total + ' avaliação' + (response.total > 1 ? 's' : '') + ')'
                    );
                }
            },
            error: function(xhr) {
                $form.find('.alert').remove();
                $form.append(
                    '<div class="alert alert-danger mt-3 mb-0 py-2 px-3" role="alert">' +
                    '<i class="fas fa-exclamation-triangle me-2"></i> Ocorreu um erro ao enviar sua avaliação.' +
                    '</div>'
                );
            },
            complete: function() {
                $form.find('button[type=submit]').prop('disabled', false);
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('submit', '.form-favorito', function(e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        var data = $form.serialize();
        var $btn = $form.find('button[type=submit]');
        $btn.prop('disabled', true);

        $.ajax({
            url: url,
            method: $form.find('input[name=_method]').val() === 'DELETE' ? 'POST' : 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                var $card = $form.closest('.artigo-card'); // Salva antes do replaceWith
                if (response.button_html) {
                    $form.replaceWith(response.button_html);
                }
                if (response.message) {
                    if ($card.length) {
                        $card.find('.alert').remove();
                        $card.prepend(
                            '<div class="alert alert-success alert-dismissible fade show mb-2" role="alert">' +
                            '<i class="fas fa-check-circle me-2"></i> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>' +
                            '</div>'
                        );
                    } else {
                        // Fallback: insere antes do form
                        $form.prev('.alert').remove();
                        $form.before(
                            '<div class="alert alert-success alert-dismissible fade show mb-2" role="alert">' +
                            '<i class="fas fa-check-circle me-2"></i> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>' +
                            '</div>'
                        );
                    }
                }
            },
            error: function() {
                alert('Erro ao favoritar/desfavoritar.');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
});
</script>
<style>
.favorito-btn:hover .fa-star,
.favorito-btn:hover .fa-star.text-warning {
    color: #ff9800 !important; /* laranja escuro, destaque no hover */
    text-shadow: 0 0 2px #fff;
    transition: color 0.15s;
}
</style>
</body>
</html>
