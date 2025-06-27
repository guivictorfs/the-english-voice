<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function logs()    { return view('admin.section', ['title' => 'Logs do Sistema']); }
}
