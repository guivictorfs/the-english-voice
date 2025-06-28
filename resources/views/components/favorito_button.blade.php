@php
    $isFavorited = $isFavorited ?? (auth()->user()->favorites->contains($article->article_id));
@endphp
@if($isFavorited)
    <form action="{{ route('articles.unfavorite', $article->article_id) }}" method="POST" class="form-favorito d-inline" data-artigo="{{ $article->article_id }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-warning px-2 py-1" title="Remover dos favoritos">
            <i class="fas fa-star text-warning"></i>
        </button>
    </form>
@else
    <form action="{{ route('articles.favorite', $article->article_id) }}" method="POST" class="form-favorito d-inline" data-artigo="{{ $article->article_id }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-warning px-2 py-1" title="Salvar nos favoritos">
            <i class="far fa-star"></i>
        </button>
    </form>
@endif
