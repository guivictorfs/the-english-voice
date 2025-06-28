@extends('layouts.app')

@section('title', 'Meus Favoritos')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Filtros de pesquisa -->
            <div class="mb-3">
                <form class="d-flex align-items-center" method="GET" action="{{ route('articles.favorites') }}" style="gap:0.25rem;">
                    <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Pesquisar artigos..." aria-label="Pesquisar" value="{{ request('q') }}" style="min-width: 180px;">
                    <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(request('q'))
                        @php
                            $query = request()->except('q');
                            $url = route('articles.favorites') . ($query ? ('?' . http_build_query($query)) : '');
                        @endphp
                        <a href="{{ $url }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpar pesquisa"><i class="fas fa-times"></i></a>
                    @endif
                </form>
            </div>
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
                    <a href="{{ route('articles.favorites') }}" class="btn btn-outline-danger btn-sm ms-2" title="Limpar filtro">
                        <i class="fas fa-times me-1"></i> Limpar filtro
                    </a>
                </div>
            @endif
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
                        <form method="GET" action="{{ route('articles.favorites') }}">
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Filtros de pesquisa -->

            <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>
    <div class="flex-grow-1 text-center">
        <h2 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Meus Favoritos</h2>
    </div>
    <div class="flex-shrink-0">
        <a href="{{ route('articles.favorites') }}" class="btn btn-outline-primary">
            <i class="fas fa-heart"></i> Favoritos
        </a>
    </div>
</div>

            @if($favorites->count())
                <div>
                    @foreach($favorites as $article)
                        <div class="artigo-card p-3 mb-4 shadow-sm border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold fs-5">{{ $article->title }}</span>
                                @include('components.favorito_button', ['article' => $article, 'isFavorited' => true])
                            </div>
                            <div class="mb-1 text-muted" style="font-size: 1rem;">
                                Por
                                @if($article->authors && $article->authors->count())
                                    @foreach($article->authors as $i => $author)
                                        <span class="fw-bold text-success">{{ $author->name }}</span>@if($i < $article->authors->count() - 1), @endif
                                    @endforeach
                                @else
                                    <span>Autor desconhecido</span>
                                @endif
                                em {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}, às {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('H:i') }}
                            </div>
                            @if($article->content)
                                <div class="mb-2 text-break overflow-hidden text-truncate text-start" style="max-height: 4.5rem; white-space: pre-line;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 200) }}
                                </div>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('artigos.visualizar', $article->article_id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-book-open"></i> Ler artigo
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $favorites->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-star me-2"></i>Você ainda não favoritou nenhum artigo.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
