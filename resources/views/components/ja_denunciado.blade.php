@php
    $jaDenunciou = $jaDenunciou ?? ($jaDenunciado ?? false);
@endphp
@if($jaDenunciou)
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center my-2 py-2 px-3" role="alert">
        <i class="fas fa-flag text-danger me-2"></i>
        Você já denunciou este artigo.
        <button type="button" class="btn-close ms-auto align-self-center pt-4" style="font-size:0.7rem;padding:0.15rem 0.4rem;min-width:1.2em;min-height:1.2em;" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif
