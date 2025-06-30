<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q){
                $sub->where('name','like',"%{$q}%")
                     ->orWhere('email','like',"%{$q}%");
            });
        }
        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $q = $request->input('q');
        return view('admin.users.index', compact('users','q'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $courses = \App\Models\Course::all(); // assuming you have a Course model
        return view('admin.users.edit', compact('user', 'courses'));
    }

    public function logs($id)
    {
        $user = User::findOrFail($id);
        $logs = DB::table('system_audit_log as l')
            ->leftJoin('users as u','u.id','=','l.id')
            ->select('l.*','u.name as performed_by')
            ->where('l.record_id',$id)
            ->orderByDesc('l.created_at')
            ->paginate(20);
        return view('admin.users.logs', compact('user','logs'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string',
            'ra' => 'nullable|string|max:20',
            'course_id' => 'required|integer|exists:course,course_id',
        ]);

        // captura valores antes e identifica mudanças
        $before = $user->only(['name','email','role','ra','course_id']);
        $details = [];
        $actionType = [];
        foreach ($data as $k => $v) {
            if ($k === 'password') {
                $details[] = 'Senha do usuário alterada';
                $actionType[] = 'Alteração de Senha';
                continue;
            }
            if ($k === 'profile_photo') {
                $details[] = 'Foto de perfil alterada';
                $actionType[] = 'Alteração de Foto';
                continue;
            }
            if (($before[$k] ?? null) != $v) {
                switch($k) {
                    case 'name':
                        $details[] = 'Nome alterado de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                        break;
                    case 'email':
                        $details[] = 'Email alterado de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                        break;
                    case 'role':
                        $details[] = 'Função alterada de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                        break;
                    case 'ra':
                        $details[] = 'RA alterado de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                        break;
                    case 'course_id':
                        $details[] = 'Curso alterado de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                        break;
                    default:
                        $details[] = ucfirst($k) . ' alterado de "' . ($before[$k] ?? 'null') . '" para "' . $v . '"';
                }
                $actionType[] = 'Alteração de Dados';
            }
        }
        $actionType = array_unique($actionType);
        $logAction = count($actionType) ? implode(' / ', $actionType) : 'Alteração';

        // se não houve alterações, não registra log
        if (empty($details)) {
            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso');
        }

        // aplica atualização
        $user->update($data);

        // registrar log
        DB::table('system_audit_log')->insert([
            'id' => auth()->id(),
            'action' => $logAction,
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => implode('; ', $details),
            'created_at' => now()
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso');
    }
}
