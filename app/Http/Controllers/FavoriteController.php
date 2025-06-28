<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class FavoriteController extends Controller
{
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

    // Lista artigos favoritados do usuário
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with(['authors', 'keywords'])->paginate(8);
        return view('favorites.index', compact('favorites'));
    }
}
