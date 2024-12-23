<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        // Verificar se o curso existe na tabela `courses`
        $course = Course::where('course_name', $request->course)->first(); // A consulta é feita na tabela courses

        if (!$course) {
            // Se o curso não for encontrado
            return redirect()->back()->withErrors(['course' => 'Curso inválido.']);
        }

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'course_id' => $course->course_id, // Armazenar o ID do curso
            'ra' => $request->ra, // Armazenar o RA do formulário
            'role' => $request->role, // Armazenar o papel do usuário
        ]);

        // Autenticar o usuário após o registro
        auth()->login($user);

        // Redirecionar para o dashboard ou página inicial
        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso!');
    }

    public function register(RegisterRequest $request)
    {
        // Validação dos campos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|in:Aluno,Professor,Administrador', // Validação do tipo de usuário
            'course' => 'required|string|max:255', // Verificação do curso
            'ra' => 'nullable|alpha_num|max:20', // RA para Alunos
        ]);

        // Validação adicional para Professores e Administradores
        if ($validatedData['role'] === 'Professor' && $validatedData['course'] !== 'Professor') {
            return back()->withErrors(['course' => 'O curso deve ser "Professor" para o tipo de usuário Professor.']);
        }

        if ($validatedData['role'] === 'Administrador' && $validatedData['course'] !== 'Administrador') {
            return back()->withErrors(['course' => 'O curso deve ser "Administrador" para o tipo de usuário Administrador.']);
        }

        // Criar o usuário no banco de dados
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
            'course' => $validatedData['course'],
            'ra' => $validatedData['ra'], // Caso o tipo de usuário seja Aluno
        ]);

        // Redirecionar para a página de login ou onde for necessário
        return redirect()->route('login')->with('status', 'Cadastro realizado com sucesso!');
    }

}
