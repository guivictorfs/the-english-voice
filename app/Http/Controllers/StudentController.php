<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    // Mostra formulário de edição de perfil
    public function profile()
    {
        $user = Auth::user();
        $courses = DB::table('course')->orderBy('course_name')->pluck('course_name', 'course_id');
        return view('students.profile_edit', compact('user', 'courses'));
    }

    // Atualiza dados do perfil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            
            'password' => 'nullable|confirmed|min:8',
        ]);

        $data = $request->only('name', 'email');
        // Foto de perfil
        if ($request->hasFile('photo')) {
            $request->validate(['photo'=>'image|mimes:jpg,jpeg,png|max:2048']);
            $path = $request->file('photo')->store('profile_photos', 'public');
            // opcional: deletar foto antiga
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $path;
            // Log no sistema de auditoria geral
            DB::table('system_audit_log')->insert([
                'id' => $user->id,
                'action' => 'Alteração de Foto',
                'table_name' => 'users',
                'record_id' => $user->id,
                'description' => 'Foto de perfil alterada',
                'created_at' => now()
            ]);
        }
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        // Detecta alterações para log
        $camposAlterados = [];
        foreach ($data as $field => $newValue) {
            $oldValue = $user->{$field};
            if ($oldValue != $newValue) {
                DB::table('user_changes')->insert([
                    'user_id'   => $user->id,
                    'field'     => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'changed_at'=> now(),
                ]);
                $camposAlterados[] = $field;
            }
        }
        DB::table('users')->where('id', $user->id)->update($data);
        // Notifica usuário se houve alteração sensível
        if (!empty($camposAlterados)) {
            $user->notify(new \App\Notifications\PerfilAlteradoNotification($camposAlterados));
        }
        return redirect()->route('students.profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
