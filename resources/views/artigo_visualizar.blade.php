<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Artigo - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
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
                <a class="nav-link" href="{{ route('dashboard') }}">Artigos</a>
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
    </ul>
            </div>
        </div>
    </nav>

<div class="container mt-4 mb-4 p-4 border border-dark">
    <div class="row justify-content-center pt-4">
        <div class="col-lg-10 col-xl-8">
            <div class="artigo-card p-4 shadow-sm border border-dark rounded mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <button onclick="window.history.back()" class="btn btn-outline-primary me-2"><i class="fas fa-arrow-left"></i> Voltar</button>
    @php $firstAuthor = $artigo->authors->first(); @endphp
    @if(auth()->check() && $firstAuthor && auth()->user()->id == $firstAuthor->id)
        <a href="{{ route('artigos.edit', $artigo->article_id) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Editar Artigo
        </a>
    @endif
</div>
                <div class="mb-3">
                    <div class="fs-3 mb-0 fw-bold text-start">Título: {{ $artigo->title }}</div>
<hr class="my-2">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        @php $firstAuthor = $artigo->authors->first(); @endphp
                        <img src="{{ $firstAuthor && $firstAuthor->profile_photo ? asset('storage/' . $firstAuthor->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($firstAuthor ? $firstAuthor->name : 'A') . '&size=96&background=cccccc&color=555555' }}" alt="Avatar" class="rounded-circle me-3" width="54" height="54">
                        <div>
                                                        <div class="text-muted" style="font-size:1rem;">
                                Por
                                @if($artigo->authors && $artigo->authors->count())
                                    @foreach($artigo->authors as $i => $author)
                                        <a href="{{ route('dashboard', ['author' => $author->name]) }}" class="fw-bold text-success text-decoration-none" title="Filtrar por {{ $author->name }}">{{ $author->name }}</a>@if($i < $artigo->authors->count() - 1), @endif
                                    @endforeach
                                @else
                                    <span>Autor desconhecido</span>
                                @endif
                                em {{ $artigo->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}, às {{ $artigo->created_at->setTimezone('America/Sao_Paulo')->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @can('update', $artigo)
                            <a href="{{ route('artigos.edit', $artigo->article_id) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="fas fa-edit"></i> Editar</a>
                        @endcan
                        @include('components.favorito_button', ['article' => $artigo])
                    </div>
                </div>
                <div class="mb-3 text-break text-start" style="white-space: pre-line;">
                    @if($artigo->content && trim($artigo->content) !== '')
                        {!! $artigo->content !!}
                    @else
                        @php
                            $file = isset($pdfPath) && $pdfPath ? (object)['file_path' => $pdfPath] : DB::table('file_upload')->where('article_id', $artigo->article_id)->orderByDesc('created_at')->first();
                        @endphp
                        @if($file && $file->file_path)
                            <hr class="my-2">
                            <div class="mb-2">
                                <strong>Arquivo PDF:</strong> <code>{{ $file->file_path }}</code><br>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir PDF em nova aba</a>
                            </div>
                            <hr class="my-2">
                            <iframe 
                                src="{{ asset('storage/' . $file->file_path) }}" 
                                width="100%" 
                                height="700px" 
                                style="border:1px solid #ccc;"></iframe>
                            <hr class="my-2">
                        @else
                            <div class="alert alert-warning">Nenhum conteúdo disponível para este artigo.</div>
                        @endif
                    @endif
                </div>
                <div class="mb-3">
                    <x-avaliacao-estrelas :artigo="$artigo" :notaUsuario="$notaUsuario ?? null" />
                </div>                
                @include('components.ja_denunciado', ['jaDenunciou' => $jaDenunciou ?? false])
                <div class="denuncia-bloco mb-2">
                    <hr class="my-2">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDenuncia-{{ $artigo->article_id }}" title="Denunciar artigo">
                        <i class="fas fa-flag"></i> Denunciar
                    </button>
                    <hr class="my-2">
                    @if(isset($artigo->denuncias) && $artigo->denuncias > 0)
                        <span class="badge bg-warning text-dark ms-2">{{ $artigo->denuncias }} denúncia{{ $artigo->denuncias > 1 ? 's' : '' }}</span>
                    @endif
                    <!-- Modal Denúncia -->
                    <div class="modal fade" id="modalDenuncia-{{ $artigo->article_id }}" tabindex="-1" aria-labelledby="modalDenunciaLabel-{{ $artigo->article_id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('artigos.denunciar', $artigo->article_id) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDenunciaLabel-{{ $artigo->article_id }}"><i class="fas fa-flag text-danger me-2"></i>Motivo da denúncia</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="motivo-{{ $artigo->article_id }}" class="form-label">Selecione o motivo:</label>
                                            <select class="form-select" name="motivo" id="motivo-{{ $artigo->article_id }}" required>
                                                <option value="">Escolha...</option>
                                                <option value="Palavra inadequada">Palavra inadequada</option>
                                                <option value="Conteúdo ofensivo">Conteúdo ofensivo</option>
                                                <option value="Plágio">Plágio</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>
                                        <div class="mb-3" id="outroMotivoDiv-{{ $artigo->article_id }}">
                                            <label for="outroMotivo-{{ $artigo->article_id }}" class="form-label">Descreva o motivo (opcional):</label>
                                            <textarea class="form-control" name="outro_motivo" id="outroMotivo-{{ $artigo->article_id }}" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger" id="btnEnviarDenuncia-{{ $artigo->article_id }}">Enviar denúncia</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

<script>
$(document).ready(function() {
    // Mostra/esconde textarea se motivo for "Outro"
    $('#motivo-{{ $artigo->article_id }}').on('change', function() {
        if ($(this).val() === 'Outro') {
            $('#outroMotivoDiv-{{ $artigo->article_id }}').show();
        } else {
            $('#outroMotivoDiv-{{ $artigo->article_id }}').hide();
            $('#outroMotivo-{{ $artigo->article_id }}').val('');
        }
    }).trigger('change');

    // Validação: se selecionou "Outro", textarea obrigatório
    $('form[action*="artigos/denunciar"]').on('submit', function(e) {
        var motivo = $('#motivo-{{ $artigo->article_id }}').val();
        var outroMotivo = $('#outroMotivo-{{ $artigo->article_id }}').val();
        if(motivo === 'Outro' && (!outroMotivo || !outroMotivo.trim())) {
            alert('Por favor, descreva o motivo da denúncia.');
            $('#outroMotivo-{{ $artigo->article_id }}').focus();
            e.preventDefault();
            return false;
        }
    });
});
</script>

                @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
                @if($artigo->keywords && $artigo->keywords->count())
                    <div class="mb-2">
                        <span class="text-secondary fw-bold me-2">Tags:</span>
                        @foreach($artigo->keywords as $kw)
                            <a href="{{ route('dashboard', ['tag' => $kw->name]) }}" class="badge bg-info text-dark me-1 text-decoration-none" title="Filtrar por tag: {{ $kw->name }}">{{ $kw->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-center pb-3">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-success"><i class="fas fa-arrow-left"></i> Voltar para o Artigos</a>
                <a href="{{ route('artigos.pdf', $artigo->article_id) }}" class="btn btn-outline-danger ms-3" target="_blank"><i class="fas fa-file-pdf"></i> Baixar PDF</a>
            </div>
        </div>
    </div>
</div>
<!-- FIM do conteúdo principal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Footer -->
<footer class="footer bg-light py-2">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        <div class="social-icons mt-3">
            <a href="https://www.linkedin.com/company/fatec-guaratinguetá/" target="_blank" class="social-link" aria-label="LinkedIn">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
            <a href="https://www.instagram.com/fatecguaratingueta/" target="_blank" class="mx-2 social-link" aria-label="Instagram">
                <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="https://www.fatecguaratingueta.edu.br" target="_blank" class="social-link" aria-label="Fatec Guaratinguetá">
                <i class="fas fa-globe fa-lg"></i>
            </a>
        </div>
    </div>
</footer>

</html>

</body>
</html>
