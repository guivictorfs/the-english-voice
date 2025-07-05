<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Busca os artigos mais recentes, com autor se houver relação
        $query = Article::with(['authors', 'keywords'])
            ->where('status', 'Aprovado');

        $tag = $request->query('tag');
        if ($tag) {
            $query->whereHas('keywords', function($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        $author = $request->query('author');
        if ($author) {
            $query->whereHas('authors', function($q) use ($author) {
                $q->where('name', $author);
            });
        }

        $q = $request->query('q');
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                     ->orWhere('content', 'like', "%$q%")
                     ->orWhereHas('authors', function($authorQ) use ($q) {
                         $authorQ->where('name', 'like', "%$q%") ;
                     })
                     ->orWhereHas('keywords', function($tagQ) use ($q) {
                         $tagQ->where('name', 'like', "%$q%") ;
                     });
            });
        }

        $articles = $query->orderByDesc('created_at')->paginate(8);

        // Carrega média e total de avaliações para cada artigo
        foreach ($articles as $article) {
            $media = \App\Models\Avaliacao::where('artigo_id', $article->article_id)->avg('nota');
            $total = \App\Models\Avaliacao::where('artigo_id', $article->article_id)->count();
            $article->media_avaliacoes = $media ? round($media, 2) : null;
            $article->total_avaliacoes = $total;
        }

        // Busca as avaliações do usuário autenticado para os artigos listados (evita N+1)
        $user = auth()->user();
        $userRatings = [];
        if ($user) {
            $articleIds = $articles->pluck('article_id')->toArray();
            $avaliacoes = \App\Models\Avaliacao::where('user_id', $user->id)
                ->whereIn('artigo_id', $articleIds)
                ->get(['artigo_id', 'nota']);
            $userRatings = $avaliacoes->pluck('nota', 'artigo_id')->toArray();
        }

        return view('dashboard', [
            'articles' => $articles,
            'tag' => $tag,
            'author' => $author,
            'q' => $q,
            'userRatings' => $userRatings,
        ]);
    }
}
