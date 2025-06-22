<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        // Busca os artigos mais recentes, com autor se houver relação
        $articles = Article::with(['author'])->orderByDesc('created_at')->get();
        return view('dashboard', compact('articles'));
    }
}
