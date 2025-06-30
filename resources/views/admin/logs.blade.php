@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-history"></i> Histórico de Atividades</h3>
                    <div class="d-flex gap-2">
                        <div class="form-group">
                            <select class="form-select" id="filterUser">
                                <option value="">Todos os usuários</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-select" id="filterAction">
                                <option value="">Todas as ações</option>
                                <option value="created">Criado</option>
                                <option value="updated">Atualizado</option>
                                <option value="deleted">Deletado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $log->causer->name }} ({{ $log->causer->email }})</td>
                                        <td>
                                            <span class="badge bg-{{ $log->properties['action'] === 'created' ? 'success' : ($log->properties['action'] === 'updated' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($log->properties['action']) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterUser = document.getElementById('filterUser');
    const filterAction = document.getElementById('filterAction');

    filterUser.addEventListener('change', function() {
        applyFilters();
    });

    filterAction.addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const user = filterUser.value;
        const action = filterAction.value;
        window.location.href = `{{ route('logs.index') }}?user=${user}&action=${action}`;
    }
});
</script>
@endpush
