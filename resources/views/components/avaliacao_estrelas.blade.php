<div class="my-4">
    <div class="card shadow-sm border-0" style="background: #f8f9fa;">
        <div class="card-body text-center">
            <h5 class="mb-3 text-primary"><i class="fas fa-star me-2"></i>Como você avalia este artigo?</h5>
            <form action="{{ route('avaliacao.store') }}" method="POST" class="d-flex flex-column align-items-center">
                @csrf
                <input type="hidden" name="artigo_id" value="{{ $artigo->article_id }}">
                <div class="mb-3 rating-stars justify-content-center d-flex flex-row-reverse">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}-{{ $artigo->article_id }}" name="nota" value="{{ $i }}" @if(isset($notaUsuario) && $notaUsuario == $i) checked @endif />
                        <label for="star{{ $i }}-{{ $artigo->article_id }}" title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}">&#9733;</label>
                    @endfor
                </div>
                @if(isset($artigo->media_avaliacoes) && $artigo->total_avaliacoes > 0)
                    <div class="mb-2">
                        <span class="fw-bold">
                            <i class="fas fa-star text-warning"></i> Nota média: {{ number_format($artigo->media_avaliacoes, 2, ',', '.') }}/5
                        </span>
                        <span class="text-muted" style="font-size:0.98em;">({{ $artigo->total_avaliacoes }} avaliação{{ $artigo->total_avaliacoes > 1 ? 's' : '' }})</span>
                    </div>
                @endif
                <button type="submit" class="btn btn-outline-primary px-4">@if(isset($notaUsuario)) Alterar Avaliação @else Enviar Avaliação @endif</button>
            </form>
            @if(isset($notaUsuario))
                <div class="alert alert-info mt-3 mb-0 py-2 px-3" role="alert">
                    <i class="fas fa-star text-warning me-2"></i> Sua avaliação atual: <strong>{{ $notaUsuario }} estrela{{ $notaUsuario > 1 ? 's' : '' }}</strong>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success mt-3 mb-0 py-2 px-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
@if(session('success'))
    <div class="alert alert-success mt-3 mb-0 py-2 px-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif
        </div>
    </div>
</div>


<style>
.rating-stars input[type="radio"] {
    display: none;
}
.rating-stars label {
    font-size: 2.8rem;
    color: #e4e5e9;
    cursor: pointer;
    transition: color 0.18s cubic-bezier(.4,2,.6,1), transform 0.15s cubic-bezier(.4,2,.6,1);
    filter: drop-shadow(0 1px 2px #bfae7c);
    text-shadow: 0 2px 8px #fffbe7, 0 0px 1px #c9a63c;
    border-radius: 0.22em;
    border: 1.5px solid transparent;
    background: linear-gradient(145deg, #fffbe7 10%, #ffe066 40%, #ffd700 60%, #ffc107 100%);
    box-shadow: 0 2px 8px #ffe06644, 0 0.5px 1.5px #d4af3770;
    margin: 0 0.08em;
    padding: 0.05em 0.05em 0.13em 0.05em;
    position: relative;
}
.rating-stars input[type="radio"]:checked ~ label,
.rating-stars input[type="radio"]:checked ~ label ~ label {
    color: #ffd700;
    background: linear-gradient(145deg, #fffbe7 10%, #ffe066 40%, #ffd700 60%, #ffc107 100%);
    box-shadow: 0 4px 16px #ffe06666, 0 0.5px 1.5px #d4af3770;
    border: 1.5px solid #ffd700;
    text-shadow: 0 2px 12px #fffbe7, 0 0px 2px #c9a63c;
    transform: scale(1.11) rotate(-3deg);
    z-index: 2;
}
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #fffbe7;
    background: linear-gradient(145deg, #ffe066 10%, #ffd700 60%, #ffc107 100%);
    box-shadow: 0 6px 22px #ffe06699, 0 0.5px 2.5px #d4af3770;
    border: 1.5px solid #ffc107;
    transform: scale(1.16) rotate(-6deg);
    z-index: 3;
}

.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffca2c;
    transform: scale(1.15);
}
.rating-stars input[type="radio"]:checked ~ label,
.rating-stars input[type="radio"]:checked ~ label ~ label {
    color: #ffc107;
    text-shadow: 0 0 6px #ffe066;
    transform: scale(1.1);
}
.rating-stars {
    gap: 0.25rem;
}
.card-body {
    padding-bottom: 1.5rem;
}
</style>
