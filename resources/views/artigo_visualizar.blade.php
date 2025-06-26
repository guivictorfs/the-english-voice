@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ $artigo->title }}</h2>
    @if($pdfPath)
    <div class="mb-2">
        <strong>Caminho PDF:</strong> <code>{{ $pdfPath }}</code><br>
        <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir PDF em nova aba</a>
    </div>
    <iframe 
        src="{{ asset('storage/' . $pdfPath) }}" 
        width="100%" 
        height="700px" 
        style="border:1px solid #ccc;">
    </iframe>
    @else
        <div class="alert alert-warning">PDF não encontrado.</div>
    @endif
</div>
@endsection
