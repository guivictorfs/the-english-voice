<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForbiddenWord;

class ForbiddenWordController extends Controller
{
    // Exibe a lista de palavras proibidas
    public function index()
    {
        $words = ForbiddenWord::orderBy('word')->get();
        return view('admin.forbidden_words', compact('words'));
    }

    // Adiciona nova palavra
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:forbidden_words,word'
        ]);

        ForbiddenWord::create([
            'word' => strtolower(trim($request->word))
        ]);

        return redirect()->back()->with('success', 'Palavra adicionada com sucesso!');
    }

    // Remove palavra proibida
    public function destroy($id)
    {
        $word = ForbiddenWord::findOrFail($id);
        $word->delete();

        return redirect()->back()->with('success', 'Palavra removida com sucesso!');
    }
}
