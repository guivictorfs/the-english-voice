<!DOCTYPE html>
<html lang="pt-br">
@php 
    use Illuminate\Support\Facades\DB; 
    use Illuminate\Support\Str; 
    use Carbon\Carbon; 
    use Illuminate\Support\Facades\Auth;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
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
                        <a class="nav-link active" href="{{ route('admin.logs.index') }}">Logs</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container p-4 flex-grow-1 mt-4 mb-4 p-4 border border-dark">
    <div class="d-flex align-items-center mb-4">
        <div class="flex-shrink-0">
            <a href="{{ route('admin.panel') }}" class="btn btn-outline-primary me-2"><i class="fas fa-arrow-left me-1"></i>Voltar ao Painel</a>
        </div>
        <div class="flex-grow-1 text-center">
            <h3><i class="fas fa-file-alt me-2 text-secondary"></i>Logs do Sistema</h3>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.logs.export', ['format' => 'pdf']) }}" class="btn btn-outline-danger">
                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label fs-5">Data Inicial</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label fs-5">Data Final</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label fs-5">Ação</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Buscar por ação" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label fs-5">Usuário</label>
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Buscar por usuário" class="form-control">
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter me-1"></i>Filtrar</button>
            @if(request()->has(['start_date', 'end_date', 'action', 'user']))
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i>Limpar Filtros</a>
            @endif
        </div>
    </form>

    <!-- Tabela de Logs -->
<div class="card mb-4 border border-dark">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Logs do Sistema</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>Data/Hora</th>
                    <th>Ação</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->record_id ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->performed_by ?? '-' }}</td>
                        <td>{{ $log->email ?? '-' }}</td>
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
</div>

<!-- Histórico de Alterações -->
<div class="card mb-4 border border-dark">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Histórico de Alterações</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-12 mb-4 mt-4">
                <h6 class="fs-5">Histórico de Artigos</h6>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID Artigo</th>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Tipo de Alteração</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(DB::table('article_history')->orderBy('created_at', 'desc')->limit(10)->get() as $history)
                            <tr>
                                <td>{{ $history->article_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                <td>{{ \App\Models\User::find($history->changed_by)->name ?? 'Usuário não encontrado' }}</td>
                                <td>{{ $history->change_type }}</td>
                                <td>{{ $history->change_description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nenhum histórico de artigos encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <h6 class="fs-5">Histórico de Comentários</h6>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID Comentário</th>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Alteração</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(DB::table('comments')->orderBy('updated_at', 'desc')->where('updated_at', '!=', 'created_at')->limit(10)->get() as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($comment->updated_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                <td>{{ \App\Models\User::find($comment->user_id)->name ?? 'Usuário não encontrado' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalComentario{{ $comment->id }}">
                                        Ver alterações
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhum histórico de comentários encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver alterações de comentários -->
@foreach(DB::table('comments')->orderBy('updated_at', 'desc')->where('updated_at', '!=', 'created_at')->limit(10)->get() as $comment)
<div class="modal fade" id="modalComentario{{ $comment->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Histórico de Comentário</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Usuário:</strong> {{ \App\Models\User::find($comment->user_id)->name ?? 'Usuário não encontrado' }}</p>
                <p><strong>Data de Criação:</strong> {{ \Carbon\Carbon::parse($comment->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                <p><strong>Última Atualização:</strong> {{ \Carbon\Carbon::parse($comment->updated_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                <hr>
                <h6>Conteúdo Atual:</h6>
                <p>{{ $comment->content }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>

<!-- FIM do conteúdo principal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            responsive: true
        });
    });
</script>
</body>
</html>