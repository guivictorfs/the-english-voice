<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artigo - The English Voice</title>
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
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @php $role = strtolower(auth()->user()->role ?? ''); @endphp
                @if($role === 'admin')
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.panel') }}">Painel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">Usuários</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.artigos.pendentes') }}">Denúncias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/courses') }}">Cursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('keywords.index') }}">Tags</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('forbidden_words.index') }}">Palavras Proibidas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.logs.index') }}">Logs</a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('students.account') }}">Meus Artigos</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('artigos.postar') }}">Postar Artigo</a>
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
                    </ul>
                @endif
                <ul class="navbar-nav ms-auto align-items-center">
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

    <div class="container p-4 mt-4 mb-4">
        <div class="d-flex align-items-center mb-4">
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-3">
                    <i class="fas fa-arrow-left me-1"></i>Voltar ao Dashboard
                </a>
            </div>
            <div class="flex-grow-1 text-center">
                <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Artigo</h2>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('artigos.update', $article->article_id) }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-labe fs-4">Título</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                <small class="form-text text-muted">O título deve ser claro e representativo do conteúdo do artigo.</small>
            </div>

            <div class="mb-4">
                <label for="autores" class="form-label fs-4">Autores</label>
                <select id="autores" name="autores[]" class="form-control" multiple required style="width:100%">
                    @foreach(App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ (collect(old('autores', $article->authors->pluck('id')->toArray()))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Selecione um ou mais autores. Clique no campo para buscar e adicione todos que participaram do artigo.</small>
                </div>

            <div class="mb-3">
                <label for="keywords" class="form-label fs-4">Tags</label>
                <input type="hidden" id="keywords-hidden" name="keywords">
                <input type="text" id="keywords" class="form-control tag-input" value="{{ old('keywords', $article->keywords->pluck('name')->implode(', ')) }}">
                <small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar.</small>
                </div>

            <!-- Verifica se é um artigo PDF -->
            @if($article->is_pdf)
                <div class="mb-3">
                    <label for="pdf" class="form-label fs-4">PDF do Artigo</label>
                    <p class="mb-2">
                        <a href="{{ asset('storage/' . $article->pdf_path) }}" target="_blank" class="text-primary">
                            <i class="fas fa-file-pdf me-1"></i>Ver PDF atual
                        </a>
                    </p>
                    <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
                    <div class="form-text">
                        Deixe em branco para manter o PDF atual. Apenas PDFs são aceitos.
                    </div>
                </div>
            @else
                <div class="mb-3">
                    <label for="content" class="form-label fs-4">Conteúdo do Artigo</label>
                    <div id="editor" style="height: 300px;"></div>
                    <input type="hidden" name="content" id="conteudo-hidden">
                </div>
            @endif

            <a href="{{ route('dashboard') }}" class="btn btn-outline-danger me-4">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        $(document).ready(function() {
            // Função para verificar palavras proibidas
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

            // Verifica palavras proibidas no título ao digitar
            $('#title').on('input', function() {
                checkForbiddenWords($(this).val(), $(this));
            });

            // Verifica palavras proibidas nas tags ao adicionar
            var tagify = new Tagify(document.getElementById('keywords'), {
                whitelist: [],
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    enabled: 0,
                    closeOnSelect: false
                }
            });

            // Atualiza a whitelist do Tagify quando o usuário digitar
            tagify.on('input', function(e) {
                if (e.detail.value.length >= 2) {
                    $.get('/api/keywords?q=' + e.detail.value, function(response) {
                        tagify.whitelist = response.map(function(item) {
                            return item.value;
                        });
                    });
                }
            });

            // Verifica palavras proibidas quando uma tag é adicionada
            tagify.on('add', function(e) {
                var allTags = tagify.value.map(function(tag) {
                    return tag.value;
                }).join(', ');
                checkForbiddenWords(allTags, $('#keywords'));
            });

            // Preenche as tags existentes (vindo do value original do input como string)
            var existingTags = $('#keywords').val().split(',').map(function(tag) {
                return tag.trim();
            }).filter(function(tag) {
                return tag !== '';
            });
            tagify.addTags(existingTags);

            // Converter tags para string separada por vírgula antes do envio do formulário
            $('form').on('submit', function() {
                var tags = tagify.value.map(function(tag) {
                    return tag.value;
                });
                $('#keywords-hidden').val(tags.join(', ')); // envia como string separada por vírgula
            });

            // Select2 para autores
            $('#autores').select2({
                width: '100%',
                placeholder: 'Selecione os autores',
                allowClear: true
            });



            // Inicializa o Quill
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'header': 1 }, { 'header': 2 }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'font': [] }],
                        [{ 'align': [] }],
                        ['clean'],
                        ['link', 'image']
                    ]
                }
            });

            // Carrega o conteúdo do artigo
            quill.clipboard.dangerouslyPasteHTML({!! json_encode(old('content', $article->content)) !!});

            // Atualiza o campo hidden quando o conteúdo muda
            quill.on('text-change', function() {
                document.getElementById('conteudo-hidden').value = quill.root.innerHTML;
            });

            // Preencher o campo hidden ao submeter
            $('form').on('submit', function() {
                $('#conteudo-hidden').val(quill.root.innerHTML);
            });
        });
    </script>
    <!-- Footer -->
<footer class="footer bg-light py-2 mt-auto">
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
</body>
</html>