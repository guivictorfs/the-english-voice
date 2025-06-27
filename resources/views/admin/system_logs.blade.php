@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Logs do Sistema</h3>

    <div class="mb-3">
        <a href="{{ route('admin.panel') }}" class="btn btn-secondary">Voltar ao Painel</a>
    </div>

    <!-- Filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Data Inicial</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Data Final</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Ação</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Buscar por ação" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Usuário</label>
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Buscar por usuário" class="form-control">
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Filtrar</button>
            @if(request()->has(['start_date', 'end_date', 'action', 'user']))
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary">Limpar Filtros</a>
            @endif
        </div>
    </form>

    <!-- Tabela de Logs -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Tabela</th>
                    <th>ID Registro</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->performed_by ?? 'Sistema' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum log encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection
