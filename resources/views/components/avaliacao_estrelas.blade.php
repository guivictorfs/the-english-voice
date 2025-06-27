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
    transition: color 0.2s, transform 0.15s;
    filter: drop-shadow(0 1px 1px #bbb);
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
