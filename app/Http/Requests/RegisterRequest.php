<?php

namespace App\Http\Requests;

use App\Enums\Course;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permitir para qualquer usuário
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users|regex:/@fatec\.sp\.gov\.br$/i',
            'password' => 'required|string|min:8',
            'ra' => 'required|string|max:20',
            'course' => 'required|string|in:' . implode(',', array_column(Course::cases(), 'value')),
            'role' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'email.regex' => 'O e-mail deve ser um endereço @fatec.sp.gov.br.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'As senhas não coincidem.',
            'course.required' => 'Selecione um curso.',
        ];
    }
}
