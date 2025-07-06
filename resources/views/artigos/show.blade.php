@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="artigo-card p-4 shadow-sm border rounded mb-4">
                <h2 class="fw-bold mb-2">{{ $article->title }}</h2>
                <div class="mb-2 text-muted" style="font-size: 1rem;">
                    Por
                    @if($article->authors && $article->authors->count())
                        @foreach($article->authors as $i => $author)
                            <span class="fw-bold text-success">{{ $author->name }}</span>@if($i < $article->authors->count() - 1), @endif
                        @endforeach
                    @else
                        <span>Autor desconhecido</span>
                    @endif
                    em {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y') }}, às {{ $article->created_at->setTimezone('America/Sao_Paulo')->format('H:i') }}
                </div>
                @if($article->keywords && $article->keywords->count())
                    <div class="mb-2">
                        <span class="text-secondary fw-bold me-2">Tags:</span>
                        @foreach($article->keywords as $kw)
                            <span class="badge bg-info text-dark me-1">{{ $kw->name }}</span>
                        @endforeach
                    </div>
                @endif
                <hr>
                <div class="mb-4 text-break" style="white-space: pre-line;">
                    {!! $article->content !!}
                </div>
                <hr>
                <div class="mb-3">
                    <span class="fw-bold">
                        <i class="fas fa-star text-warning"></i> Nota média: {{ $article->media_avaliacoes ?? 'Sem avaliações' }}/5
                    </span>
                    <span class="text-muted ms-2">({{ $article->total_avaliacoes }} avaliação{{ $article->total_avaliacoes == 1 ? '' : 's' }})</span>
                </div>
                <div class="mb-3">
                    @include('components.avaliacao_estrelas', ['artigo' => $article, 'notaUsuario' => $notaUsuario])
                </div>
                <div class="mt-2">
                    @include('components.favorito_button', ['article' => $article])
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar para o Artigos</a>
        </div>
    </div>
</div>
<div class="card my-4">
    <div class="card-header">Comentários</div>
    <div class="card-body">
        @auth
            <form action="{{ route('comentarios.store', $article->article_id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" maxlength="2000" required placeholder="Escreva seu comentário..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar comentário</button>
            </form>
        @else
            <p class="text-muted">Faça login para comentar.</p>
        @endauth
        <hr>
        @foreach($article->comments()->where('hidden', false)->latest()->get() as $comment)
            <div class="mb-3">
                <strong>{{ $comment->user->name }}</strong> <span class="text-muted" style="font-size:0.9em;">{{ $comment->created_at->diffForHumans() }}</span>
                @if(auth()->check() && (auth()->user()->is_admin || auth()->id() == $comment->user_id))
                    <div class="float-end">
                        <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalEditarComentario{{ $comment->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluirComentario{{ $comment->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endif
                <div>{{ $comment->content }}</div>
            </div>
            <!-- Modal de edição de comentário -->
            <div class="modal fade" id="modalEditarComentario{{ $comment->id }}" tabindex="-1" aria-labelledby="modalEditarComentarioLabel{{ $comment->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarComentarioLabel{{ $comment->id }}">Editar Comentário</h5>
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
            <!-- Modal de confirmação de exclusão -->
            <div class="modal fade" id="modalExcluirComentario{{ $comment->id }}" tabindex="-1" aria-labelledby="modalExcluirComentarioLabel{{ $comment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="modalExcluirComentarioLabel{{ $comment->id }}">Excluir Comentário</h5>
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
                @endif
                <div>{{ $comment->content }}</div>
            </div>
            <!-- Modal de confirmação de exclusão -->
            <div class="modal fade" id="modalExcluirComentario{{ $comment->id }}" tabindex="-1" aria-labelledby="modalExcluirComentarioLabel{{ $comment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="modalExcluirComentarioLabel{{ $comment->id }}">Excluir Comentário</h5>
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
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Função para atualizar comentário via AJAX
    function updateCommentViaAjax(form) {
        const formData = new FormData(form);
        const commentId = form.dataset.commentId;
        
        fetch(form.action, {
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
</script>
@endsection
