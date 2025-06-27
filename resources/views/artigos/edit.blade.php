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
        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="content" name="content" rows="8">{{ old('content', $article->content) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
