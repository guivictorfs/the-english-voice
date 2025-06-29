@php
    $isFavorited = $isFavorited ?? (auth()->user()->favorites->contains($article->article_id));
@endphp
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

<style>
.favorito-btn:hover .fa-star,
.favorito-btn:hover .fa-star.text-warning {
    color: #ff9800 !important; /* laranja escuro, destaque no hover */
    text-shadow: 0 0 2px #fff;
    transition: color 0.15s;
}
</style>
