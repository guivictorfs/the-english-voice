@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Logs de {{ $user->name }}</h3>

    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Ação</th>
                    <th>Por</th>
                    <th>Tabela</th>
                    <th>ID Registro</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->performed_by ?? '-' }}</td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum log encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection
