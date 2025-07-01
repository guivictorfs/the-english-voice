<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Article;
use App\Models\ArticleReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminPanelController extends Controller
{
    public function suspiciousActivities(Request $request)
    {
        $query = \App\Models\SuspiciousActivity::with('user')->orderBy('created_at', 'desc');

        // Filtro por nome do usuário
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }
        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }
        // Filtro por data
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $activities = $query->get();
        return view('admin.suspicious_activities', compact('activities'));
    }

    public function runSuspiciousVotesCheck()
    {
        Artisan::call('votes:check-suspicious');
        $output = Artisan::output();
        return redirect()->back()->with('success', 'Verificação executada!<br><pre>' . e($output) . '</pre>');
    }

    public function users()
    {
        $users = \App\Models\User::orderByDesc('created_at')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    public function articles(){ return view('admin.section', ['title' => 'Gerenciar Artigos']); }
    public function reports()
    {
        // Contagem de artigos por status
        $articleStatusCounts = \App\Models\Article::whereIn('status', ['pending', 'approved', 'rejected', 'review'])
            ->groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Contagem de usuários por role
        $userRoleCounts = \App\Models\User::whereIn('role', ['student', 'teacher', 'admin'])
            ->groupBy('role')
            ->selectRaw('role, count(*) as count')
            ->get()
            ->pluck('count', 'role')
            ->toArray();

// Removendo temporariamente a seção de avaliações por nota
        $ratingCounts = [];

        // Denúncias pendentes
        $reports = \App\Models\ArticleReport::with(['article', 'article.authors', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Artigos pendentes de aprovação
        $pendingArticles = \App\Models\Article::where('status', 'pending')
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.reports', compact('articleStatusCounts', 'userRoleCounts', 'ratingCounts', 'reports', 'pendingArticles'));
    }

    public function courses() { return view('admin.section', ['title' => 'Cursos']); }
    public function keywords(){ return view('admin.section', ['title' => 'Palavras-chave']); }
    public function logs(Request $request)
    {
        $query = DB::table('system_audit_log as l')
            ->leftJoin('users as u', 'u.id', '=', 'l.id')
            ->select('l.*', 'u.name as performed_by', 'u.email as email')
            ->orderByDesc('l.created_at');

        // Filtros
        if ($request->filled('start_date')) {
            $query->whereDate('l.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('l.created_at', '<=', $request->end_date);
        }
        if ($request->filled('action')) {
            $action = $request->action;
            $query->where(function($q) use ($action) {
                $q->where('l.action', 'like', "%{$action}%")
                  ->orWhere('l.description', 'like', "%{$action}%");
            });
        }
        if ($request->filled('user')) {
            $user = $request->user;
            $query->where(function($q) use ($user) {
                $q->where('u.name', 'like', "%{$user}%")
                  ->orWhere('u.email', 'like', "%{$user}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.system_logs', compact('logs'));
    }

    /**
     * Exibe os artigos relacionados à atividade suspeita de um usuário
     */
    public function suspiciousUserDetails($userId, $type)
    {
        $user = User::findOrFail($userId);
        $artigos = collect();
        $tipo = $type;
        if ($type === 'muitas_denuncias') {
            $artigos = \App\Models\ArticleReport::with('article')
                ->where('user_id', $userId)
                ->get()
                ->pluck('article')
                ->unique('article_id');
        } elseif ($type === 'many_low_votes') {
            $artigos = \App\Models\Avaliacao::with('artigo')
                ->where('user_id', $userId)
                ->where('nota', 1)
                ->get()
                ->pluck('artigo')
                ->unique('article_id');
        }
        return view('admin.suspicious_user_details', compact('user', 'tipo', 'artigos'));
    }

    /**
     * Marcar atividade suspeita como revisada
     */
    public function markSuspiciousActivityReviewed($id)
    {
        $activity = \App\Models\SuspiciousActivity::findOrFail($id);
        $activity->reviewed = true;
        $activity->save();
        return redirect()->back()->with('success', 'Atividade marcada como revisada!');
    }

    /**
     * Marcar atividade suspeita como NÃO revisada
     */
    public function markSuspiciousActivityUnreviewed($id)
    {
        $activity = \App\Models\SuspiciousActivity::findOrFail($id);
        $activity->reviewed = false;
        $activity->save();
        return redirect()->back()->with('success', 'Atividade marcada como NÃO revisada!');
    }
}
