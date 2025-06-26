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
        $articles = $query->orderByDesc('created_at')->get();
        return view('dashboard', ['articles' => $articles, 'tag' => $tag]);
    }
}
