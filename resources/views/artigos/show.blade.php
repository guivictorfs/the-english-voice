@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="artigo-card p-4 shadow-sm border rounded mb-4">
                <h2 class="fw-bold mb-2">{{ $article->title }}</h2>
                <div class="mb-2 text-muted" style="font-size: 1rem;">
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
                @if($article->keywords && $article->keywords->count())
                    <div class="mb-2">
                        <span class="text-secondary fw-bold me-2">Tags:</span>
                        @foreach($article->keywords as $kw)
                            <span class="badge bg-info text-dark me-1">{{ $kw->name }}</span>
                        @endforeach
                    </div>
                @endif
                <hr>
                <div class="mb-4 text-break" style="white-space: pre-line;">
                    {!! $article->content !!}
                </div>
                <hr>
                <div class="mb-3">
                    <span class="fw-bold">
                        <i class="fas fa-star text-warning"></i> Nota média: {{ $article->media_avaliacoes ?? 'Sem avaliações' }}/5
                    </span>
                    <span class="text-muted ms-2">({{ $article->total_avaliacoes }} avaliação{{ $article->total_avaliacoes == 1 ? '' : 's' }})</span>
                </div>
                <div class="mb-3">
                    @include('components.avaliacao_estrelas', ['artigo' => $article, 'notaUsuario' => $notaUsuario])
                </div>
                <div class="mt-2">
                    @include('components.favorito_button', ['article' => $article])
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
        </div>
    </div>
</div>
@endsection
