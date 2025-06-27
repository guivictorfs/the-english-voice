<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Excluir artigo do aluno autenticado
    public function destroy($id)
    {
        $userId = Auth::id();
        // Verifica se o usuário é autor principal do artigo
        $isPrincipal = DB::table('article_author')
            ->where('article_id', $id)
            ->where('id', $userId)
            ->where('author_type', 'Principal')
            ->exists();
        if (!$isPrincipal) {
            return redirect()->back()->withErrors('Você não tem permissão para excluir este artigo.');
        }
        // Remove vínculos de autores
        DB::table('article_author')->where('article_id', $id)->delete();
        // Remove o artigo
        DB::table('article')->where('article_id', $id)->delete();
        return redirect()->route('students.account')->with('success', 'Artigo excluído com sucesso!');
    }
    // Exibe os artigos do aluno autenticado
    public function account()
    {
        // Supondo que o relacionamento seja User -> articles
        $userId = Auth::id();
        $articles = DB::table('article')
    ->join('article_author', 'article.article_id', '=', 'article_author.article_id')
    ->where('article_author.id', $userId)
    ->where('article_author.author_type', 'Principal')
    ->select(
        'article.*',
        'article_author.author_type',
        DB::raw('COALESCE(article.denuncias,0) as denuncias'),
DB::raw('(SELECT ROUND(AVG(rating),1) FROM vote WHERE vote.article_id = article.article_id) as media_nota')
    )
    ->orderByDesc('article.created_at')
    ->get();
        // Calcula média e total de avaliações de cada artigo
        foreach ($articles as $article) {
            $media = DB::table('avaliacoes')->where('artigo_id', $article->article_id)->avg('nota');
            $total = DB::table('avaliacoes')->where('artigo_id', $article->article_id)->count();
            $article->media_avaliacoes = $media ? round($media, 2) : null;
            $article->total_avaliacoes = $total;
        }
        return view('students.account', compact('articles'));
    }
}
