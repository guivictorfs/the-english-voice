<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPanelController extends Controller
{
    public function users()
    {
        $users = \App\Models\User::orderByDesc('created_at')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    public function articles(){ return view('admin.section', ['title' => 'Gerenciar Artigos']); }
    public function reports() { return view('admin.section', ['title' => 'Denúncias']); }
    public function courses() { return view('admin.section', ['title' => 'Cursos']); }
    public function keywords(){ return view('admin.section', ['title' => 'Palavras-chave']); }
    public function logs(Request $request)
    {
        $query = DB::table('system_audit_log as l')
            ->leftJoin('users as u', 'u.id', '=', 'l.id')
            ->select('l.*', 'u.name as performed_by')
            ->orderByDesc('l.created_at');

        // Filtros
        if ($request->filled('start_date')) {
            $query->whereDate('l.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('l.created_at', '<=', $request->end_date);
        }
        if ($request->filled('action')) {
            $query->where('l.action', 'like', "%{$request->action}%");
        }
        if ($request->filled('user')) {
            $query->where('u.name', 'like', "%{$request->user}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.system_logs', compact('logs'));
    }
}
