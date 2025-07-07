<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyAdminAccess;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        // Não precisamos de middleware no construtor
        // O middleware será aplicado diretamente nas rotas
    }

    public function store(Request $request, $article_id)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);
        
        // Filtro de palavras proibidas
        $forbiddenWords = \App\Models\ForbiddenWord::all();
        $foundWords = [];
        foreach ($forbiddenWords as $word) {
            if (stripos($request->content, $word->word) !== false) {
                $foundWords[] = $word->word;
            }
        }
        if (!empty($foundWords)) {
            return back()->withInput()->with('error', 'Comentário contém palavras proibidas: ' . implode(', ', $foundWords));
        }
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->article_id = $article_id;
        $comment->content = $request->content;
        $comment->save();
        // Notifica autores do artigo (exceto o comentarista)
        $article = \App\Models\Article::with('authors')->find($article_id);
        $user = Auth::user();
        if ($article) {
            foreach ($article->authors as $author) {
                if ($author->id != $user->id) {
                    $author->notify(new \App\Notifications\NovoComentarioNotification($article->title, $user->name, $comment->content, $article->article_id));
                }
            }
        }
        return back()->with('success', 'Comentário enviado com sucesso!');
    }

    public function update(Request $request, $comment_id)
    {
        try {
            // Validação do conteúdo
            $request->validate([
                'content' => 'required|string|max:2000',
            ]);
            
            // Log do conteúdo recebido
            \Illuminate\Support\Facades\Log::info('Atualizando comentário', [
                'comment_id' => $comment_id,
                'content_recebido' => $request->input('content'),
                'user_id' => auth()->id()
            ]);
            
            // Busca o comentário
            $comment = Comment::findOrFail($comment_id);
            
            // Log do comentário encontrado
            \Illuminate\Support\Facades\Log::info('Comentário encontrado', [
                'comment_id' => $comment->id,
                'current_content' => $comment->content,
                'user_id' => $comment->user_id
            ]);
            
            // Verifica se é admin
            if (auth()->check() && in_array(auth()->user()->role, ['Admin', 'Professor'])) {
                // Admin pode editar qualquer comentário
                $comment->content = $request->input('content');
                $comment->save();
                \Illuminate\Support\Facades\Log::info('Comentário atualizado por admin', [
                    'comment_id' => $comment->id,
                    'new_content' => $comment->content
                ]);
                return response()->json([
                    'success' => true,
                    'comment' => [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user_id' => $comment->user_id,
                        'created_at' => $comment->created_at->format('d/m/Y H:i'),
                        'user_name' => $comment->user->name,
                        'user_photo' => $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=48&background=cccccc&color=222222'
                    ]
                ]);
            }
            
            // Se não for admin, verifica se é dono do comentário
            if ($comment->user_id !== Auth::id()) {
                \Illuminate\Support\Facades\Log::warning('Tentativa de edição não autorizada', [
                    'comment_id' => $comment->id,
                    'user_id' => auth()->id(),
                    'comment_user_id' => $comment->user_id
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Você não tem permissão para editar este comentário.'
                ], 403);
            }
            
            // Filtro de palavras proibidas
            $forbiddenWords = \App\Models\ForbiddenWord::all();
            $foundWords = [];
            foreach ($forbiddenWords as $word) {
                if (stripos($request->input('content'), $word->word) !== false) {
                    $foundWords[] = $word->word;
                }
            }
            
            if (!empty($foundWords)) {
                \Illuminate\Support\Facades\Log::warning('Palavras proibidas encontradas', [
                    'comment_id' => $comment->id,
                    'found_words' => $foundWords
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Comentário contém palavras proibidas: ' . implode(', ', $foundWords)
                ], 403);
            }
            
            // Atualiza o comentário
            $comment->content = $request->input('content');
            $comment->save();
            
            // Log da atualização
            \Illuminate\Support\Facades\Log::info('Comentário atualizado', [
                'comment_id' => $comment->id,
                'new_content' => $comment->content
            ]);
            
            // Retorna o comentário atualizado
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_id' => $comment->user_id,
                    'created_at' => $comment->created_at->format('d/m/Y H:i'),
                    'user_name' => $comment->user->name,
                    'user_photo' => $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=48&background=cccccc&color=222222'
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar comentário', [
                'comment_id' => $comment_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Erro ao atualizar comentário. Por favor, tente novamente.'
            ], 500);
        }
    }

    public function report(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $userId = auth()->id();
        // Verifica se o usuário já denunciou este comentário
        $alreadyReported = DB::table('comment_reports')
            ->where('comment_id', $comment_id)
            ->where('user_id', $userId)
            ->exists();
        if ($alreadyReported) {
            return back()->with('error', 'Você já denunciou este comentário.');
        }
        // Registra denúncia
        DB::table('comment_reports')->insert([
            'comment_id' => $comment_id,
            'user_id' => $userId,
            'motivo' => $request->input('motivo', 'Inadequado'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Conta denúncias únicas
        $totalReports = DB::table('comment_reports')
            ->where('comment_id', $comment_id)
            ->count();
        // Se atingir 5 denúncias, marca como denunciado
        if ($totalReports >= 1 && !$comment->reported) {
            $comment->reported = true;
            $comment->save();
        }
        return back()->with('success', 'Comentário denunciado com sucesso!');
    }

    public function aprovarComentario(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        
        // Remove todas as denúncias relacionadas a este comentário
        DB::table('comment_reports')
            ->where('comment_id', $comment_id)
            ->delete();
            
        // Remove a marcação de denunciado
        $comment->reported = false;
        $comment->save();

        return back()->with('success', 'Comentário aprovado com sucesso!');
    }

    public function excluirComentario(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        
        // Verifica se o usuário é admin ou o autor do comentário
        if (!auth()->check() || (!in_array(auth()->user()->role, ['Admin', 'Professor']) && auth()->id() !== $comment->user_id)) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este comentário.');
        }
        
        // Remove todas as denúncias relacionadas
        DB::table('comment_reports')
            ->where('comment_id', $comment_id)
            ->delete();
            
        // Remove o comentário
        $comment->delete();

        // Redireciona para a página correta
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.artigos.pendentes')->with('success', 'Comentário excluído com sucesso!');
        } else {
            return redirect()->back()->with('success', 'Comentário excluído com sucesso!');
        }
    }
}
