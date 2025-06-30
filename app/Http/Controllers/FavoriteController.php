<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class FavoriteController extends Controller
{
    // Lista favoritos do usuário com filtros
    public function index()
    {
        $user = Auth::user();
        
        $query = Article::with(['authors', 'keywords'])
            ->whereHas('favoritedBy', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filtro por título ou conteúdo
        if (request('q')) {
            $searchTerm = request('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por tags
        if (request('tags')) {
            $tags = request('tags');
            if (is_string($tags)) {
                $tags = [$tags];
            }
            $query->whereHas('keywords', function($q) use ($tags) {
                $q->whereIn('keyword', $tags);
            });
        }

        // Filtro por autor
        if (request('author')) {
            $author = request('author');
            $query->whereHas('authors', function($q) use ($author) {
                $q->where('name', 'like', '%' . $author . '%');
            });
        }

        $favorites = $query->paginate(10);

        return view('favorites.index', compact('favorites'));
    }
    // Adiciona artigo aos favoritos
    public function store(Request $request, $article_id)
    {
        $user = Auth::user();
        $article = Article::findOrFail($article_id);
        $user->favorites()->syncWithoutDetaching([$article->article_id]);
        if ($request->ajax()) {
            $buttonHtml = view('components.favorito_button', [
                'article' => $article,
                'isFavorited' => true
            ])->render();
            return response()->json([
                'message' => 'Artigo adicionado aos favoritos!',
                'favorited' => true,
                'button_html' => $buttonHtml
            ]);
        }
        return back()->with([
            'success' => 'Artigo adicionado aos favoritos!',
            'fav_article_id' => $article->article_id
        ]);
    }

    // Remove artigo dos favoritos
    public function destroy(Request $request, $article_id)
    {
        $user = Auth::user();
        $article = Article::findOrFail($article_id);
        $user->favorites()->detach($article->article_id);
        if ($request->ajax()) {
            $buttonHtml = view('components.favorito_button', [
                'article' => $article,
                'isFavorited' => false
            ])->render();
            return response()->json([
                'message' => 'Artigo removido dos favoritos!',
                'favorited' => false,
                'button_html' => $buttonHtml
            ]);
        }
        return back()->with([
            'success' => 'Artigo removido dos favoritos!',
            'fav_article_id' => $article->article_id
        ]);
    }
}
