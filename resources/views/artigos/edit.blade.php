@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Artigo</h2>

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
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $article->title) }}" required>
        </div>

        <div class="mb-4">
            <label for="autores" class="form-label">Autores</label>
            <select id="autores" name="autores[]" class="form-control" multiple required style="width:100%">
                @foreach(App\Models\User::orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}" {{ (collect(old('autores', $article->authors->pluck('id')->toArray()))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Selecione um ou mais autores. Clique no campo para buscar e adicione todos que participaram do artigo.</small>
        </div>

        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <input name="keywords" id="keywords" class="form-control" placeholder="Ex: Grammar, Vocabulary, Reading, Writing, Speaking, Listening" value="{{ old('keywords', $article->keywords->pluck('name')->implode(', ')) }}">
            <small class="form-text text-muted">Digite uma palavra-chave e pressione <b>Enter</b> ou <b>vírgula</b> para adicionar.</small>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="content" name="content" rows="8">{{ old('content', $article->content) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
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
        // Quill (opcional, só se for usar editor avançado)
        // var quill = new Quill('#editor', { theme: 'snow' });
        // $('#form-artigo').on('submit', function() {
        //     $('#conteudo').val(quill.root.innerHTML);
        // });
    </script>
@endpush

@endsection
