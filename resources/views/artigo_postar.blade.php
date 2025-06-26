@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Artigo enviado com sucesso!</strong><br>
                <span class="small">Seu artigo foi postado e está aguardando revisão ou publicação.</span>
            </div>
        </div>
    @endif
    @if($errors->has('pdf_min_caracteres'))
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-type me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>PDF com texto insuficiente!</strong><br>
                <span class="small">{{ $errors->first('pdf_min_caracteres') }}</span>
            </div>
        </div>
    @elseif($errors->has('pdf_sem_texto'))
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-file-earmark-image me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>PDF não contém texto pesquisável!</strong><br>
                <span class="small">O arquivo enviado parece ser apenas uma imagem ou digitalização. Envie um PDF pesquisável (com texto selecionável) para que possamos revisar o conteúdo.</span>
            </div>
        </div>
    @endif
    @if($errors->has('pdf_palavra_inadequada'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-emoji-frown me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>PDF contém palavras inadequadas!</strong><br>
                <span class="small">O arquivo PDF enviado contém palavras ofensivas. Remova essas palavras e envie novamente.</span>
            </div>
        </div>
    @endif
    @if($errors->any() && !$errors->has('pdf_sem_texto') && !$errors->has('pdf_palavra_inadequada'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Erro ao enviar artigo!</strong><br>
                <span class="small">Por favor, corrija os itens abaixo e tente novamente:</span>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <h2 class="mb-4">Postar Artigo</h2>
    <div class="row mb-4">
        <div class="col-md-6 mb-2">
            <div class="card card-option h-100 shadow-sm text-center p-4 cursor-pointer border-primary" id="card-escrever" style="cursor:pointer;">
                <div class="card-body">
                    <i class="fas fa-pen-nib fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">Escrever artigo</h5>
                    <p class="card-text">Digite e formate seu texto diretamente no site</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="card card-option h-100 shadow-sm text-center p-4 cursor-pointer border-success" id="card-upload" style="cursor:pointer;">
                <div class="card-body">
                    <i class="fas fa-file-upload fa-2x text-success mb-2"></i>
                    <h5 class="card-title">Enviar PDF</h5>
                    <p class="card-text">Faça upload de um arquivo PDF já pronto</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Formulário de escrever artigo -->
    <form id="form-artigo" action="{{ route('artigos.store') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="tipo_formulario" value="escrever">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="autores" class="form-label">Autores</label>
            <select id="autores" name="autores[]" class="form-control" multiple required>
                @foreach(App\Models\User::orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <input name="keywords" class="form-control" placeholder="Digite uma palavra-chave e pressione Enter ou vírgula">
<small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar. Repita para várias keywords.</small>
        </div>
        <div class="mb-3">
    <label for="editor" class="form-label">Conteúdo</label>
        <div id="editor" style="height: 300px;"></div>
        <div class="mt-2"><span id="char-count" class="text-muted">Caracteres: 0/50</span></div>
    <input type="hidden" name="conteudo" id="conteudo">
</div>
        <button type="submit" class="btn btn-primary" id="submit-artigo" disabled>Postar artigo</button>
    </form>
    <!-- Formulário de upload de PDF -->
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
            <input name="keywords" id="keywords-pdf" class="form-control" placeholder="Digite e pressione Enter para adicionar">
        </div>
        <div class="mb-3">
            <label for="pdf" class="form-label">Arquivo PDF</label>
            <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar PDF</button>
    </form>
    <!-- Quill.js CDN -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- Select2 CSS -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- Tagify CSS -->
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cards de seleção
            const cardEscrever = document.getElementById('card-escrever');
            const cardUpload = document.getElementById('card-upload');
            const formEscrever = document.getElementById('form-artigo');
            const formUpload = document.getElementById('form-pdf');

            cardEscrever.onclick = function() {
                formEscrever.classList.remove('d-none');
                formUpload.classList.add('d-none');
                cardEscrever.classList.add('border-3','border-primary');
                cardUpload.classList.remove('border-3','border-success');
            };
            cardUpload.onclick = function() {
                formUpload.classList.remove('d-none');
                formEscrever.classList.add('d-none');
                cardUpload.classList.add('border-3','border-success');
                cardEscrever.classList.remove('border-3','border-primary');
            };

            // Inicializa Quill apenas uma vez ao carregar a página
            window.quill = new Quill('#editor', {
                theme: 'snow'
            });
            // Sincroniza Quill ao enviar
            formEscrever.onsubmit = function() {
                document.getElementById('conteudo').value = window.quill.root.innerHTML;
            };
            // Contador de caracteres para Quill
            const charCount = document.getElementById('char-count');
            const submitBtn = document.getElementById('submit-artigo');
            function updateCharCount() {
                const text = window.quill.getText().replace(/\s/g, ''); // conta só caracteres reais
                const chars = text.length;
                charCount.textContent = `Caracteres: ${chars}/50`;
                submitBtn.disabled = chars < 50;
            }
            window.quill.on('text-change', updateCharCount);
            updateCharCount(); // inicial
        });
    </script>
    <!-- Select2 JS -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
<!-- Tagify JS -->
<script src="{{ asset('js/tagify.min.js') }}"></script>
    <script>
        // Select2 para autores
        $(document).ready(function() {
            $('#autores').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
        // Tagify para keywords
        var input = document.querySelector('input[name=keywords]');
        if (input) {
            new Tagify(input);
        }
        // Alternância de formulário (escrever/upload)
        $(function() {
            $('#card-escrever').on('click', function() {
                $('#form-escrever').show();
                $('#form-upload').hide();
                $('#card-escrever').addClass('border-primary');
                $('#card-upload').removeClass('border-success');
            });
            $('#card-upload').on('click', function() {
                $('#form-escrever').hide();
                $('#form-upload').show();
                $('#card-escrever').removeClass('border-primary');
                $('#card-upload').addClass('border-success');
            });
        });
        // Contador de caracteres para Quill
        if (typeof Quill !== 'undefined') {
            const quill = new Quill('#editor', { theme: 'snow' });
            const charCount = document.getElementById('char-count');
            const submitBtn = document.getElementById('submit-artigo');
            function updateCharCount() {
                const text = quill.getText().replace(/\s/g, ''); // conta só caracteres reais
                const chars = text.length;
                charCount.textContent = `Caracteres: ${chars}/50`;
                submitBtn.disabled = chars < 50;
            }
            quill.on('text-change', updateCharCount);
            updateCharCount(); // inicial
        }
    </script>
    <script>
        // Select2 para autores
        $(document).ready(function() {
            $('#autores').select2({
                placeholder: 'Selecione um ou mais autores',
                allowClear: true
            });
            $('#autores-pdf').select2({
                placeholder: 'Selecione um ou mais autores',
                allowClear: true
            });
        });

        // Tagify para keywords com autocomplete
        function tagifyWithAjax(input) {
            if (!input) return;
            new Tagify(input, {
                enforceWhitelist: false,
                delimiters: ",", // aceita vírgula
                dropdown: {
                    enabled: 1, // mostra sugestões ao digitar
                    maxItems: 15,
                    classname: 'tags-look',
                    fuzzySearch: true,
                    highlightFirst: true
                },
                whitelist: [],
                // Busca dinâmica
                callbacks: {
                    'input': function(e) {
                        var value = e.detail.value;
                        fetch('/api/keywords?q=' + encodeURIComponent(value))
                            .then(r => r.json())
                            .then(data => {
                                input.tagify.settings.whitelist = data;
                                input.tagify.dropdown.show.call(input.tagify, value);
                            });
                    }
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                // Só previne se dropdown do Tagify estiver aberto ou se o campo está focado
                if (input.tagify && input.tagify.dropdown.isVisible) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
    </script>
    <style>
        .card-option { transition: box-shadow .2s, border .2s; cursor:pointer; }
        .card-option:hover, .card-option.border-3 { box-shadow: 0 0 0 4px #0d6efd22; border-width:3px!important; }
    </style>
</div>
@endsection
