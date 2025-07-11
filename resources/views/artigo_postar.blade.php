<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postar Artigos - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    @vite('resources/css/artigo_postar.css')
    <style>
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
        .tagify__input::placeholder {
            color: #bbb;
            opacity: 1;
            text-align: center;
        }
        .ql-toolbar {
            background: #fff;
        }
        .ql-editor {
            background: #fff;
        }
    </style>
</head>
<body>
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
                        <a class="nav-link" href="{{ route('dashboard') }}">Artigos</a>
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

    <div class="container mt-5 mb-5 p-4 border border-dark">
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
            <div class="card card-option-escrever shadow-sm text-center p-4 border-primary" id="card-escrever">
                <i class="fas fa-pen-nib fa-2x text-primary mb-2"></i>
                <h5>Escrever artigo</h5>
                <p>Digite e formate seu texto diretamente no site</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-option-pdf shadow-sm text-center p-4 border-success" id="card-upload">
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
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite um título descritivo para seu artigo" required>
            <small class="form-text text-muted">O título deve ser claro e representativo do conteúdo do artigo.</small>
        </div>

        <div class="mb-4">
            <label for="autores" class="form-label fs-4"><b>Autores</b></label>
            <select id="autores" name="autores[]" class="form-control" multiple required style="width:100%">
                @foreach(App\Models\User::whereIn('role', ['Aluno', 'Professor'])->orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}" {{ (collect(old('autores', []))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Selecione um ou mais autores. Clique no campo para buscar e adicione todos que participaram do artigo.</small>
        </div>

        <div class="mb-3">
            <label for="keywords" class="form-label fs-4"><b>Tags</b></label>
            <input type="hidden" id="keywords-hidden" name="keywords">
            <input type="text" id="keywords" class="form-control tag-input" placeholder="Ex: Grammar, Vocabulary, Reading, Writing, Speaking, Listening" value="{{ old('keywords') }}">
            <small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar.</small>
        </div>

        <div class="mb-3">
            <label for="editor" class="form-label fs-4"><b>Conteúdo</b></label>
            <div class="editor-container">
                <div id="editor-artigo" class="editor-container" style="height: 300px; background: #fff; border: 1px solid #ced4da; border-radius: 6px; padding: 15px;"></div>
            </div>
            <input type="hidden" name="conteudo" id="conteudo-hidden">
        </div>
        <div class="mt-2"><span id="char-count" class="text-muted">Caracteres: 0/250</span></div>

        <button type="submit" class="btn btn-primary" id="submit-artigo" disabled>Postar artigo</button>
    </form>

    {{-- Formulário - PDF --}}
    <form id="form-pdf" action="{{ route('artigos.store') }}" method="POST" enctype="multipart/form-data" class="d-none">
        @csrf
        <input type="hidden" name="tipo_formulario" value="pdf">

        <div class="mb-3">
            <label for="titulo-pdf" class="form-label fs-4"><b>Título</b></label>
            <input type="text" class="form-control" id="titulo-pdf" name="titulo" placeholder="Digite um título descritivo para seu artigo" required>
            <small class="form-text text-muted">O título deve ser claro e representativo do conteúdo do artigo.</small>
        </div>

        <div class="mb-4">
            <label for="autores-pdf" class="form-label fs-4"><b>Autores</b></label>
            <select id="autores-pdf" name="autores[]" class="form-control" multiple required style="width:100%">
                @foreach(App\Models\User::whereIn('role', ['Aluno', 'Professor'])->orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Selecione um ou mais autores. Clique no campo para buscar e adicione todos que participaram do artigo.</small>
        </div>

        <div class="mb-3">
            <label for="keywords-pdf" class="form-label fs-4"><b>Keywords</b></label>
            <input type="hidden" id="keywords-pdf-hidden" name="keywords">
            <input type="text" id="keywords-pdf" class="form-control tag-input" placeholder="Ex: Grammar, Vocabulary, Reading, Writing, Speaking, Listening" value="{{ old('keywords') }}">
            <small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar.</small>
        </div>

        <div class="mb-3">
            <label for="pdf" class="form-label fs-4"><b>Arquivo PDF</b></label>
            <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf" required>
            <small class="form-text text-muted">Selecione um arquivo PDF com seu artigo pronto para publicação.</small>
        </div>

        <button type="submit" class="btn btn-primary">Enviar PDF</button>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@vite('resources/css/welcome.css')
    
<style>
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
    .tagify__input::placeholder {
        color: #bbb;
        opacity: 1;
        text-align: center;
    }
    .ql-toolbar {
        background: #fff;
    }
    .ql-editor {
        background: #fff;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
$(document).ready(function() {
    // --- TAGIFY ---
    const tagify = new Tagify(document.querySelector('#keywords'), {
        whitelist: [],
        maxTags: 10,
        dropdown: {
            maxItems: 20,
            enabled: 0,
            closeOnSelect: false
        }
    });
    const tagifyPdf = new Tagify(document.querySelector('#keywords-pdf'), {
        whitelist: [],
        maxTags: 10,
        dropdown: {
            maxItems: 20,
            enabled: 0,
            closeOnSelect: false
        }
    });
    // Sugestão dinâmica
    [tagify, tagifyPdf].forEach(tag => {
        tag.on('input', function(e) {
            if (e.detail.value.length >= 2) {
                $.get('/api/keywords?q=' + e.detail.value, function(response) {
                    tag.whitelist = response.map(item => item.value);
                });
            }
        });
        tag.on('add', function(e) {
            var allTags = tag.value.map(function(tag) { return tag.value; }).join(', ');
            checkForbiddenWords(allTags, $(tag.DOM.input));
        });
    });
    // Sincronização dos campos hidden no submit
    $('form').on('submit', function(e) {
        const isPDF = this.id === 'form-pdf';
        const tagifyInstance = isPDF ? tagifyPdf : tagify;
        const tags = tagifyInstance.value.map(tag => tag.value).join(', ');
        $(isPDF ? '#keywords-pdf-hidden' : '#keywords-hidden').val(tags);
    });
    // Validação de palavras proibidas
    function checkForbiddenWords(text, element) {
        $.ajax({
            url: '/api/check-forbidden-words',
            method: 'POST',
            data: {
                text: text,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.hasForbiddenWords) {
                    element.addClass('is-invalid');
                    element.next('.invalid-feedback').remove();
                    element.after('<div class="invalid-feedback">Contém palavras proibidas: ' + response.forbiddenWords.join(', ') + '</div>');
                } else {
                    element.removeClass('is-invalid');
                    element.next('.invalid-feedback').remove();
                }
            }
        });
    }
    // --- SELECT2 ---
    $('#autores, #autores-pdf').select2({
        placeholder: 'Selecione um ou mais autores',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4',
        dropdownAutoWidth: true,
        minimumResultsForSearch: 0,
        language: {
            noResults: function () { return "Nenhum autor encontrado."; }
        }
    });
    // --- QUILL ---
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
    // Contador de caracteres
    var charCount = document.getElementById('char-count');
    var submitBtn = document.getElementById('submit-artigo');
    function updateCharCount() {
        var chars = quill.getText().replace(/\s/g, '').length;
        charCount.textContent = `Caracteres: ${chars}/250`;
        submitBtn.disabled = chars < 250;
    }
    quill.on('text-change', updateCharCount);
    updateCharCount();
    // Alternância dos cards
    const cardEscrever = document.getElementById('card-escrever');
    const cardUpload = document.getElementById('card-upload');
    const formArtigo = document.getElementById('form-artigo');
    const formPDF = document.getElementById('form-pdf');
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
});
</script>
});
    </script>
</body>
</html>
<footer class="footer bg-light py-2">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        <div class="social-icons mt-3">
            <a href="https://www.linkedin.com/company/fatec-guaratinguetá/" target="_blank" class="social-link" aria-label="LinkedIn">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
            <a href="https://www.instagram.com/fatecguaratingueta/" target="_blank" class="mx-2 social-link" aria-label="Instagram">
                <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="https://www.fatecguaratingueta.edu.br" target="_blank" class="social-link" aria-label="Fatec Guaratinguetá">
                <i class="fas fa-globe fa-lg"></i>
            </a>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

