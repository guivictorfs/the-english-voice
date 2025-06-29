<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $artigo->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
        h1 { font-size: 2rem; margin-bottom: 0.5rem; }
        .authors { font-size: 1rem; color: #333; margin-bottom: 1.5rem; }
        .content { font-size: 1.1rem; margin-top: 1rem; white-space: pre-line; }
    </style>
</head>
<body>
    <h1>{{ $artigo->title }}</h1>
    <div class="authors">
        <strong>Autor{{ $artigo->authors->count() > 1 ? 'es' : '' }}:</strong>
        {{ $artigo->authors->pluck('name')->join(', ') }}
    </div>
    <div class="content">
        {!! $artigo->content !!}
    </div>
</body>
</html>
