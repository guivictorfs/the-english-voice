<!DOCTYPE html>
<html lang="pt-br">
@php use Illuminate\Support\Facades\DB; use Illuminate\Support\Str; use Carbon\Carbon; use Illuminate\Support\Facades\Auth; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denúncias - The English Voice</title>
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
                        <a class="nav-link active" href="{{ route('admin.artigos.pendentes') }}">Denúncias</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-4 p-4 border border-dark">
        <div class="d-flex align-items-center mb-4">
            <div class="flex-shrink-0">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Voltar ao Painel</a>
            </div>
            <div class="flex-grow-1 text-center">
                <h2><i class="fas fa-flag me-2 text-danger"></i>Artigos Denunciados para Revisão</h2>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if($articles->count())
            <ul class="list-group border border-dark">
                @foreach($articles as $article)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @php
    $author = $article->authors->first();
    $avatar = $author && $author->profile_photo ? asset('storage/'.$author->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($author ? $author->name : 'A') . '&size=60&background=cccccc&color=555555';
@endphp
<img src="{{ $avatar }}" alt="Avatar" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
<strong>{{ $article->title }}</strong>
                                <button type="button" class="badge bg-warning text-dark ms-2 border-0" data-bs-toggle="modal" data-bs-target="#modalDenuncias-{{ $article->article_id }}">
                                    {{ $article->denuncias }} denúncia{{ $article->denuncias > 1 ? 's' : '' }}
                                </button>
                                <!-- Modal de denúncias -->
                                <div class="modal fade" id="modalDenuncias-{{ $article->article_id }}" tabindex="-1" aria-labelledby="modalDenunciasLabel-{{ $article->article_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDenunciasLabel-{{ $article->article_id }}">Denúncias para: {{ $article->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if(isset($reports[$article->article_id]) && $reports[$article->article_id]->count())
                                                    <ul class="list-group">
                                                        @foreach($reports[$article->article_id] as $report)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-user"></i> {{ $report->user ? $report->user->name : 'Aluno desconhecido' }}</span>
                                                                <span class="ms-3"><i class="fas fa-comment"></i> {{ $report->motivo }}</span>
                                                                <span class="text-muted small">{{ $report->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="text-muted">Nenhuma denúncia registrada.</div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('artigos.edit', $article->article_id) }}" class="btn btn-outline-primary btn-sm me-1"><i class="fas fa-edit"></i> Editar</a>
                                <a href="{{ route('artigos.visualizar', $article->article_id) }}" class="btn btn-outline-info btn-sm me-1" target="_blank" title="Visualizar Artigo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAprovar-{{ $article->article_id }}">
                                    <i class="fas fa-check"></i> Aprovar
                                </button>
                                <!-- Modal de confirmação de aprovação -->
                                <div class="modal fade" id="modalAprovar-{{ $article->article_id }}" tabindex="-1" aria-labelledby="modalAprovarLabel-{{ $article->article_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="modalAprovarLabel-{{ $article->article_id }}">Aprovar Artigo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Confirmação:</strong> O artigo será <span class="text-success">republicado</span> e ficará visível novamente para todos os usuários.<br>
                                                Tem certeza que deseja aprovar e republicar este artigo?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.artigos.aprovar', $article->article_id) }}" method="POST" class="d-inline m-0 p-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Aprovar e republicar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluir-{{ $article->article_id }}">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                                <!-- Modal de confirmação -->
                                <div class="modal fade" id="modalExcluir-{{ $article->article_id }}" tabindex="-1" aria-labelledby="modalExcluirLabel-{{ $article->article_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="modalExcluirLabel-{{ $article->article_id }}">Excluir Artigo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <strong>ATENÇÃO:</strong> Esta ação é <span class="text-danger">permanente e irreversível</span>.<br>
                                                Tem certeza que deseja excluir este artigo para sempre?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.artigos.excluir', $article->article_id) }}" method="POST" class="d-inline m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Excluir definitivamente</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            @if(isset($article->content) && $article->content !== null && trim($article->content) !== '')
                                <div class="mb-2">{!! Str::limit($article->content, 400) !!}</div>
                            @else
                                @php
                                    $file = DB::table('file_upload')
                                        ->where('article_id', $article->article_id)
                                        ->orderByDesc('created_at')
                                        ->first();
                                @endphp
                                @if($file)
                                    <div class="mb-2">
                                        <strong>Arquivo PDF:</strong>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Abrir PDF
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted">Nenhum arquivo disponível.</div>
                                @endif
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info">Nenhum artigo pendente de revisão.</div>
        @endif

        {{-- Bloco de Comentários Denunciados separado --}}
        @if(isset($reportedComments) && $reportedComments->count())
            <div class=" mt-4 mb-4 p-4 border border-dark rounded bg-white shadow-sm">
                <h3 class="text-dark mb-4"><i class="fas fa-flag me-2 text-danger"></i>Comentários Denunciados para Revisão</h3>
                <ul class="list-group">
                    @foreach($reportedComments as $comment)
    <div class="mb-4 p-4 border border-dark rounded shadow-sm">
        <li class="list-group-item bg-transparent border-0 p-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $comment->user ? $comment->user->name : 'Usuário desconhecido' }}</strong>
                    em <a href="{{ route('artigos.visualizar', $comment->article_id) }}" target="_blank">{{ $comment->article ? $comment->article->title : 'Artigo desconhecido' }}</a>
                    <span class="ms-2 badge bg-danger"><i class="fas fa-flag"></i> Denunciado</span>
                    <br>
                    <span class="text-muted small">{{ $comment->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
                    <div class="mt-2 border rounded p-2 bg-white">{{ $comment->content }}</div>
                </div>
                <div>
                                <button type="button" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEditarComentario-{{ $comment->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <a href="{{ route('artigos.visualizar', $comment->article_id) }}" class="btn btn-outline-info btn-sm me-1" target="_blank" title="Visualizar Artigo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAprovarComentario-{{ $comment->id }}">
                                    <i class="fas fa-check"></i> Aprovar
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluirComentario-{{ $comment->id }}">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </div>
                            <!-- Modal de confirmação de aprovação -->
                            <div class="modal fade" id="modalAprovarComentario-{{ $comment->id }}" tabindex="-1" aria-labelledby="modalAprovarComentarioLabel-{{ $comment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="modalAprovarComentarioLabel-{{ $comment->id }}">Aprovar Comentário</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <strong>Confirmação:</strong> O comentário será <span class="text-success">aprovado</span> e ficará visível novamente para todos os usuários.<br>
                                            Tem certeza que deseja aprovar este comentário?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.comentarios.aprovar', $comment->id) }}" method="POST" class="d-inline m-0 p-0">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Aprovar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal de confirmação de exclusão -->
                            <div class="modal fade" id="modalExcluirComentario-{{ $comment->id }}" tabindex="-1" aria-labelledby="modalExcluirComentarioLabel-{{ $comment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="modalExcluirComentarioLabel-{{ $comment->id }}">Excluir Comentário</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <strong>ATENÇÃO:</strong> Esta ação é <span class="text-danger">permanente e irreversível</span>.<br>
                                            Tem certeza que deseja excluir este comentário para sempre?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.comentarios.excluir', $comment->id) }}" method="POST" class="d-inline m-0 p-0">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Excluir definitivamente</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal de Edição do Comentário -->
                            <div class="modal fade" id="modalEditarComentario-{{ $comment->id }}" tabindex="-1" aria-labelledby="modalEditarComentarioLabel-{{ $comment->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditarComentarioLabel-{{ $comment->id }}">Editar Comentário</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('comentarios.update', $comment->id) }}" method="POST" class="needs-validation" novalidate data-comment-id="{{ $comment->id }}" onsubmit="return updateCommentViaAjax(this)">
                                                @csrf
                                                <div class="mb-3">
                                                    <textarea name="content" class="form-control" rows="3" required>{{ $comment->content }}</textarea>
                                                    <strong>Atenção:</strong> O comentário será atualizado imediatamente após salvar.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para atualizar comentário via AJAX
        function updateCommentViaAjax(form) {
            const formData = new FormData(form);
            const commentId = form.dataset.commentId;
            
            fetch(form.action.replace('comentarios.update', 'admin.comentarios.update'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Atualiza o conteúdo do comentário
                    const commentContent = document.getElementById('comment-content-' + commentId);
                    if (commentContent) {
                        commentContent.innerHTML = data.comment.content;
                    }
                    
                    // Fecha o modal de edição
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarComentario-' + commentId));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Mostra mensagem de sucesso
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success mt-2';
                    successAlert.textContent = 'Comentário atualizado com sucesso!';
                    form.parentElement.insertBefore(successAlert, form);
                    
                    // Remove a mensagem após 3 segundos
                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
                } else {
                    // Mostra mensagem de erro
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger mt-2';
                    errorAlert.textContent = data.error || 'Erro ao atualizar comentário.';
                    form.parentElement.insertBefore(errorAlert, form);
                    
                    // Remove a mensagem após 3 segundos
                    setTimeout(() => {
                        errorAlert.remove();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar comentário:', error);
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger mt-2';
                errorAlert.textContent = 'Erro ao atualizar comentário. Por favor, tente novamente.';
                form.parentElement.insertBefore(errorAlert, form);
                
                // Remove a mensagem após 3 segundos
                setTimeout(() => {
                    errorAlert.remove();
                }, 3000);
            });
            
            // Previne o envio padrão do formulário
            return false;
        }
        
        // Função para recarregar a página após a atualização do comentário
        function reloadAfterUpdate() {
            // Verifica se existe um comentário atualizado na URL
            const commentUpdated = new URLSearchParams(window.location.search).get('comment_updated');
            if (commentUpdated) {
                // Recarrega a página após 1 segundo
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }
        
        // Inicia a verificação quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona validação ao enviar o formulário
            document.querySelectorAll('form.needs-validation').forEach(form => {
                form.addEventListener('submit', function(event) {
                    console.log('Formulário enviado:', {
                        action: this.action,
                        method: this.method,
                        content: this.content.value
                    });
                    
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            });
            
            reloadAfterUpdate();
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