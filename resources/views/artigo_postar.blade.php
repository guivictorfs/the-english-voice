@extends('layouts.app')
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('students.account') }}">Meus Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('artigos.postar') }}">Postar Artigo</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@section('content')
<div class="container mt-5 mb-5 pb-4">
    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Artigo enviado com sucesso!</strong><br>
                <span class="small">Seu artigo foi postado com sucesso.</span>
            </div>
        </div>
    @endif

    {{-- Outros erros --}}
    @foreach (['pdf_min_caracteres', 'pdf_sem_texto', 'pdf_palavra_inadequada'] as $error)
        @if($errors->has($error))
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>Erro no PDF:</strong><br>
                    <span class="small">{{ $errors->first($error) }}</span>
                </div>
            </div>
        @endif
    @endforeach

    @if($errors->any() && !$errors->has('pdf_sem_texto') && !$errors->has('pdf_palavra_inadequada'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Erro ao enviar artigo!</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <h2 class="pb-4 pt-4">Postar Artigo</h2>

    {{-- Escolha entre escrever ou upload --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-option shadow-sm text-center p-4 border-primary" id="card-escrever">
                <i class="fas fa-pen-nib fa-2x text-primary mb-2"></i>
                <h5>Escrever artigo</h5>
                <p>Digite e formate seu texto diretamente no site</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-option shadow-sm text-center p-4 border-success" id="card-upload">
                <i class="fas fa-file-upload fa-2x text-success mb-2"></i>
                <h5>Enviar PDF</h5>
                <p>Faça upload de um arquivo PDF já pronto</p>
            </div>
        </div>
    </div>

    {{-- Formulário - Escrever --}}
    <form id="form-artigo" action="{{ route('artigos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tipo_formulario" value="escrever">

        <div class="mb-3">
            <label for="titulo" class="form-label fs-4"><b>Título</b></label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>

        <div class="mb-4">
            <label for="autores" class="form-label fs-4"><b>Autores</b></label>
            <select id="autores" name="autores[]" class="form-control" multiple required style="width:100%">
                @foreach(App\Models\User::orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}" {{ (collect(old('autores', []))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Selecione um ou mais autores. Clique no campo para buscar e adicione todos que participaram do artigo.</small>
        </div>

        <div class="mb-3">
            <label for="keywords" class="form-label fs-4"><b>Keywords</b></label>
            <input name="keywords" id="keywords" class="form-control" placeholder="Ex: Grammar, Vocabulary, Reading, Writing, Speaking, Listening" value="{{ old('keywords') }}">
            <small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar.</small>
        </div>

        <div class="mb-3">
            <label for="editor" class="form-label fs-4"><b>Conteúdo</b></label>
            <div id="editor-artigo" style="height: 300px;"></div>
            <input type="hidden" name="conteudo" id="conteudo-hidden">
        </div>
        <div class="mt-2"><span id="char-count" class="text-muted">Caracteres: 0/50</span></div>

        <button type="submit" class="btn btn-primary" id="submit-artigo" disabled>Postar artigo</button>
    </form>

    {{-- Formulário - PDF --}}
    <form id="form-pdf" action="{{ route('artigos.store') }}" method="POST" enctype="multipart/form-data" class="d-none">
        @csrf
        <input type="hidden" name="tipo_formulario" value="pdf">

        <div class="mb-3">
            <label for="titulo-pdf" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo-pdf" name="titulo" required>
        </div>

        <div class="mb-3">
            <label for="autores-pdf" class="form-label">Autores</label>
            <select id="autores-pdf" name="autores[]" class="form-control" multiple required>
                @foreach(App\Models\User::orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="keywords-pdf" class="form-label">Keywords</label>
            <input name="keywords" id="keywords-pdf" class="form-control" placeholder="Digite e pressione Enter ou vírgula">
        </div>

        <div class="mb-3">
            <label for="pdf" class="form-label">Arquivo PDF</label>
            <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf" required>
        </div>

        <button type="submit" class="btn btn-primary">Enviar PDF</button>
    </form>
</div>

{{-- Estilos --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css" rel="stylesheet">

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/tagify.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seletores dos cards e formulários
    const cardEscrever = document.getElementById('card-escrever');
    const cardUpload = document.getElementById('card-upload');
    const formArtigo = document.getElementById('form-artigo');
    const formPDF = document.getElementById('form-pdf');

    // Quill com toolbar avançada
    var quill = new Quill('#editor-artigo', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }, { 'size': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }, { 'align': [] }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ]
        }
    });
    // Preencher o campo hidden ao submeter
    $('#form-artigo').on('submit', function() {
        $('#conteudo-hidden').val(quill.root.innerHTML);
    });
    // Atualizar contador de caracteres
    var charCount = document.getElementById('char-count');
    var submitBtn = document.getElementById('submit-artigo');
    function updateCharCount() {
        var chars = quill.getText().replace(/\s/g, '').length;
        charCount.textContent = `Caracteres: ${chars}/50`;
        submitBtn.disabled = chars < 50;
    }
    quill.on('text-change', updateCharCount);
    updateCharCount();

    // Alternância dos cards
    cardEscrever.addEventListener('click', () => {
        formArtigo.classList.remove('d-none');
        formPDF.classList.add('d-none');
        cardEscrever.classList.add('border-3','border-primary');
        cardUpload.classList.remove('border-3','border-success');
    });
    cardUpload.addEventListener('click', () => {
        formPDF.classList.remove('d-none');
        formArtigo.classList.add('d-none');
        cardUpload.classList.add('border-3','border-success');
        cardEscrever.classList.remove('border-3','border-primary');
    });

    // Select2 para autores
    $('#autores, #autores-pdf').select2({
        placeholder: 'Selecione um ou mais autores',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4',
        dropdownAutoWidth: true,
        minimumResultsForSearch: 0, // Permite busca sempre
        language: {
            noResults: function () {
                return "Nenhum autor encontrado.";
            }
        }
    });


    // Tagify para keywords
    [ 'keywords', 'keywords-pdf' ].forEach(function (id) {
        var input = document.getElementById(id);
        if (input) new Tagify(input, { delimiters: "," });
    });
});
</script>

<style>
    .card-option { transition: box-shadow .2s, border .2s; cursor:pointer; }
    .card-option:hover, .card-option.border-3 { box-shadow: 0 0 0 4px #0d6efd22; border-width:3px!important; }

    /* SELECT2 - Melhor visual para múltiplos autores */
    .select2-container--bootstrap4 .select2-selection--multiple {
        min-height: 46px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        background-color: #fff;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #e9f5ff;
        border-color: #b6e0fe;
        color: #007bff;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        margin-left: 0.4rem;
        color: #007bff;
        font-weight: bold;
        cursor: pointer;
    }

    .select2-container--bootstrap4 .select2-search--inline .select2-search__field {
        width: auto !important;
        min-width: 120px !important;
        margin: 0;
    }
</style>
@push('styles')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Tagify -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <!-- Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar {
            background: #fff;
        }
        .ql-editor {
            background: #fff;
        }
        /* Tagify placeholder e layout */
        .tagify {
            width: 100%;
            min-height: 38px;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #ced4da;
        }
        .tagify__input {
            min-width: 200px;
            color: #888;
            opacity: 1;
            padding: 6px 8px;
        }
        .tagify--focus .tagify__input::placeholder {
            color: #bbb;
            opacity: 1;
        }
        .tagify__input::placeholder {
            color: #bbb;
            opacity: 1;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery (necessário para Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Tagify -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <!-- Quill -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        $(document).ready(function() {
            $('#autores').select2({
                width: '100%',
                placeholder: 'Selecione os autores',
                allowClear: true
            });
        });
        // Tagify
        var input = document.querySelector('input[name=keywords]');
        if(input) { new Tagify(input); }
        // Quill com toolbar avançada
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }, { 'align': [] }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                ]
            }
        });
        // Preencher o campo hidden ao submeter
        $('#form-artigo').on('submit', function() {
            $('#conteudo-hidden').val(quill.root.innerHTML);
        });
    </script>
@endpush

@endsection
