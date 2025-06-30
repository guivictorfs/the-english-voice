@extends('layouts.app')

@section('content')
<!-- Styles para esta página -->
@push('styles')
<style>
    .report-card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Removendo efeitos de hover */
    .btn, .btn:hover, .btn:focus, .btn:active {
        transform: none !important;
        box-shadow: none !important;
        opacity: 1 !important;
    }

    .table tr:hover {
        background-color: transparent !important;
    }

    .card:hover {
        transform: none !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        margin-bottom: 0;
    }
    .btn-group {
        gap: 0.5rem;
    }
    .badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }
    .badge-info {
        background-color: #0dcaf0;
        color: #000;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
    .card-header {
        padding: 1rem;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }
    .card-body {
        padding: 1.5rem;
    }
    .modal-content {
        border-radius: 10px;
    }
    .modal-header {
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }
    .modal-footer {
        border-top: 2px solid rgba(0,0,0,0.1);
    }
</style>
@endpush

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Denúncias de Artigos</h3>
        <div>
            <a href="{{ route('admin.panel') }}" class="btn btn-secondary me-2">Voltar ao Painel</a>
            <button class="btn btn-primary" onclick="exportAllData()">
                <i class="fas fa-file-export me-1"></i> Exportar Todos os Dados
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form id="filtersForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Período</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="startDate" name="start_date">
                            <span class="input-group-text">até</span>
                            <input type="date" class="form-control" id="endDate" name="end_date">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Denúncia</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">Todos</option>
                            <option value="plagiarism">Plágio</option>
                            <option value="inappropriate">Conteúdo Inapropriado</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filtrar Denúncias
                </button>
            </form>
        </div>
    </div>

    <!-- Tabela de Denúncias -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Denúncias Pendentes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th>Denunciante</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td><a href="{{ route('artigos.visualizar', $report->article->article_id) }}" target="_blank">{{ $report->article->title }}</a></td>
                                <td>{{ $report->article->authors->first()->name ?? 'Autor não encontrado' }}</td>
                                <td>{{ $report->motivo }}</td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $report->user->name ?? 'Usuário não encontrado' }}</td>
                                <td>{{ $report->descricao }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="approveReport({{ $report->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectReport({{ $report->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma denúncia pendente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    /********** FILTROS **********/
    const form       = document.getElementById('filtersForm');
    const startInput = document.getElementById('startDate');
    const endInput   = document.getElementById('endDate');
    const typeInput  = document.getElementById('typeFilter');

    // Preenche campos com parâmetros da URL
    window.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(location.search);
        if (params.has('start_date')) startInput.value = params.get('start_date');
        if (params.has('end_date'))   endInput.value   = params.get('end_date');
        if (params.has('type'))       typeInput.value  = params.get('type');
        // Função para verificar se a data está dentro do período
        function isWithinPeriod(reportDate, startDate, endDate) {
            if (!startDate && !endDate) return true;
            const reportDateISO = formatDate(reportDate);
            if (startDate && endDate) {
                return reportDateISO >= formatDate(startDate) && reportDateISO <= formatDate(endDate);
            }
            if (startDate) {
                return reportDateISO >= formatDate(startDate);
            }
            if (endDate) {
                return reportDateISO <= formatDate(endDate);
            }
            return true;
        }

        // Função para aplicar filtros na página
        function applyFilters() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter').value;
            
            document.querySelectorAll('tbody tr').forEach(row => {
                const reportDate = row.querySelector('td:nth-child(4)').textContent;
                const reportStatus = row.querySelector('td:nth-child(3)').textContent;
                const reportType = row.querySelector('td:nth-child(6)').textContent;
                
                const showRow = 
                    isWithinPeriod(reportDate, startDate, endDate) &&
                    (!status || reportStatus === status) &&
                    (!type || reportType === type);
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        // Aplicar filtros quando a página carregar
        applyFilters();

        // Atualizar filtros quando os valores mudarem
        document.querySelectorAll('#startDate, #endDate, #statusFilter, #typeFilter').forEach(input => {
            input.addEventListener('change', applyFilters);
        });

        // Exportar todos os dados
        function exportAllData() {
            // Implementar exportação de todos os dados
            console.log('Exportando todos os dados...');
        }

        // Funções de confirmação
        function showConfirm(title, message, action, type = 'default') {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            document.querySelector('.modal-title').textContent = title;
            document.querySelector('.modal-body p').textContent = message;
            
            const confirmBtn = document.querySelector('.confirm-action');
            confirmBtn.textContent = 'Confirmar';
            confirmBtn.className = 'btn btn-primary confirm-action';
            
            if (type === 'success') {
                confirmBtn.className = 'btn btn-success confirm-action';
            } else if (type === 'danger') {
                confirmBtn.className = 'btn btn-danger confirm-action';
            }

            confirmBtn.onclick = action;
            modal.show();
        }

        function approveReport(id) {
            showConfirm(
                'Aprovar Denúncia',
                'Tem certeza que deseja aprovar esta denúncia?',
                () => {
                    // Implementar lógica de aprovação
                    console.log('Aprovando denúncia:', id);
                },
                'success'
            );
            'Tem certeza que deseja rejeitar esta denúncia?',
            () => {
                // Implementar lógica de rejeição
                console.log('Rejeitando denúncia:', id);
            },
            'danger'
        );
    }

    function approveArticle(id) {
        showConfirm(
            'Aprovar Artigo',
            'Tem certeza que deseja aprovar este artigo?',
            () => {
                // Implementar lógica de aprovação
                console.log('Aprovando artigo:', id);
            },
            'success'
        );
    }

    function rejectArticle(id) {
        showConfirm(
            'Rejeitar Artigo',
            'Tem certeza que deseja rejeitar este artigo?',
            () => {
                // Implementar lógica de rejeição
                console.log('Rejeitando artigo:', id);
            },
            'danger'
        );
    }

    // Formulário de filtros
    document.getElementById('filtersForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const status = document.getElementById('statusFilter').value;
        
        // Implementar filtro dos dados
        console.log('Filtrando por:', { startDate, endDate, status });
    });

    // Exportar todos os dados
    function exportAllData() {
        // Implementar exportação de todos os dados
        console.log('Exportando todos os dados...');
    }

    const ctxRole = document.getElementById('roleChart').getContext('2d');
    const roleChart = new Chart(ctxRole, {
        type: 'pie',
        data: {
            labels: ['Aluno', 'Professor', 'admin'],
            datasets: [{
                data: [{{ $userRoleCounts['student'] ?? 0 }}, {{ $userRoleCounts['teacher'] ?? 0 }}, {{ $userRoleCounts['admin'] ?? 0 }}],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        }
    });



    // Funções de confirmação
    function showConfirm(title, message, action) {
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        document.querySelector('.modal-title').textContent = title;
        document.querySelector('.modal-body p').textContent = message;
        document.querySelector('.confirm-action').onclick = action;
        modal.show();
    }

    function approveReport(id) {
        showConfirm('Aprovar Denúncia', 'Tem certeza que deseja aprovar esta denúncia?', () => {
            // Implementar lógica de aprovação
        });
    }

    function rejectReport(id) {
        showConfirm('Rejeitar Denúncia', 'Tem certeza que deseja rejeitar esta denúncia?', () => {
            // Implementar lógica de rejeição
        });
    }

    function approveArticle(id) {
        showConfirm('Aprovar Artigo', 'Tem certeza que deseja aprovar este artigo?', () => {
            // Implementar lógica de aprovação
        });
    }

    function rejectArticle(id) {
        showConfirm('Rejeitar Artigo', 'Tem certeza que deseja rejeitar este artigo?', () => {
            // Implementar lógica de rejeição
        });
    }
</script>
@endsection
