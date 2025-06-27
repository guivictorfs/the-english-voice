<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avaliacao;

class AvaliacaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'artigo_id' => 'required|exists:article,article_id',
            'nota' => 'required|integer|min:1|max:5',
        ]);

        Avaliacao::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'artigo_id' => $request->artigo_id
            ],
            [
                'nota' => $request->nota
            ]
        );

        return redirect()->back()->with('success', 'Avaliação registrada com sucesso!');
    }
}
