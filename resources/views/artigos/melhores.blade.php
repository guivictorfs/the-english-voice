@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-primary"><i class="fas fa-trophy text-warning"></i> Artigos com Melhor Nota Média</h2>
<p class="mb-4 text-muted text-center" style="max-width:700px; margin-left:auto; margin-right:auto;">
    <i class="fas fa-info-circle me-1"></i>
    Só aparecem artigos com pelo menos 5 avaliações. O ranking usa média ponderada bayesiana, considerando a média geral do site, para garantir justiça entre artigos muito avaliados e recém-avaliados.
</p>
    <div class="row">
        @forelse ($artigos as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i>
                                {{ number_format($artigo->avaliacoes_avg_nota, 2, ',', '.') }}/5
                            </span>
                            <span class="text-muted ms-2">({{ $artigo->avaliacoes_count }} avaliações)</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-warning btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado ainda.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-success"><i class="fas fa-fire text-danger"></i> Melhor nota do mês</h3>
    <div class="row">
        @forelse ($maisAvaliadosMes as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-success text-white">
    <i class="fas fa-star"></i>
    Nota ponderada do mês: {{ number_format($artigo->media_ponderada_mes, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-success btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado neste mês.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-primary"><i class="fas fa-calendar-alt text-info"></i> Melhor nota do ano</h3>
    <div class="row">
        @forelse ($maisAvaliadosAno as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-info shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-info text-white">
    <i class="fas fa-star"></i>
    Nota ponderada do ano: {{ number_format($artigo->media_ponderada_ano, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-info btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado neste ano.</div>
            </div>
        @endforelse
    </div>

    <hr class="my-5">
    <h3 class="mb-4 text-warning"><i class="fas fa-list-ol text-warning"></i> Melhor nota geral</h3>
    <div class="row">
        @forelse ($maisAvaliadosGeral as $artigo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $artigo->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-user"></i>
                            @foreach($artigo->authors as $autor)
                                {{ $autor->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark">
    <i class="fas fa-star"></i>
    Nota ponderada geral: {{ number_format($artigo->media_ponderada, 2, ',', '.') }}/5
</span>
                        </p>
                        <a href="{{ route('artigos.visualizar', $artigo->article_id) }}" class="btn btn-outline-warning btn-sm mt-2">
                            Ver artigo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum artigo avaliado no geral.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
