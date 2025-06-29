<div class="my-4 position-relative">
    <span id="badge-nota-media-{{ $artigo->article_id }}" class="badge bg-warning text-dark position-absolute top-0 end-0 mt-2 me-2 d-none" style="cursor:pointer;z-index:10;font-size:1.1em;" onclick="abrirAvaliacao{{ $artigo->article_id }}()">
        <i class="fas fa-star"></i> {{ number_format($artigo->media_avaliacoes, 2, ',', '.') }}/5
    </span>
    <div class="card shadow-sm border-0 position-relative avaliacao-card" id="avaliacao-card-{{ $artigo->article_id }}" style="background: #f8f9fa;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Fechar" onclick="fecharAvaliacao{{ $artigo->article_id }}()"></button>
        <div class="card-body text-center">
            <h5 class="mb-3 text-primary"><i class="fas fa-star me-2"></i>Como você avalia este artigo?</h5>
            <form action="{{ route('avaliacao.store') }}" method="POST" class="d-flex flex-column align-items-center form-avaliacao" data-artigo="{{ $artigo->article_id }}">
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
            @if(session('success_'.$artigo->article_id))
                <div class="alert alert-success mt-3 mb-0 py-2 px-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success_'.$artigo->article_id) }}
                </div>
            @elseif(isset($notaUsuario))
                 <div class="alert alert-info mt-3 mb-0 py-2 px-3" role="alert">
                     <i class="fas fa-star text-warning me-2"></i> Sua avaliação atual: <strong>{{ $notaUsuario }} estrela{{ $notaUsuario > 1 ? 's' : '' }}</strong>
                 </div>
            @endif
        </div>
    </div>
</div>
<script>
function fecharAvaliacao{{ $artigo->article_id }}() {
    document.getElementById('avaliacao-card-{{ $artigo->article_id }}').style.display = 'none';
    document.getElementById('badge-nota-media-{{ $artigo->article_id }}').classList.remove('d-none');
}
function abrirAvaliacao{{ $artigo->article_id }}() {
    document.getElementById('avaliacao-card-{{ $artigo->article_id }}').style.display = '';
    document.getElementById('badge-nota-media-{{ $artigo->article_id }}').classList.add('d-none');
}
</script>

<style>
.badge.bg-warning.text-dark:hover {
    background: #ff9800 !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(255, 168, 0, 0.2);
    transition: background 0.15s, color 0.15s, box-shadow 0.15s;
}
</style>



<style>
.rating-stars input[type="radio"]{display:none;}
.rating-stars label{
    font-size:2rem;
    color:#dcdcdc;
    cursor:pointer;
    transition:color .15s ease-in-out,transform .15s ease-in-out;
}
.rating-stars input[type="radio"]:checked ~ label,
.rating-stars input[type="radio"]:checked ~ label ~ label{
    color:#f1c40f;
}
.rating-stars label:hover,
.rating-stars label:hover ~ label{
    color:#f1c40f;
    transform:scale(1.1);
}
.rating-stars{gap:.15rem;}
.card-body{padding-bottom:1.3rem;}
</style>
