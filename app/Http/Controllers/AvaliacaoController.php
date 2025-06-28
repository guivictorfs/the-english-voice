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

        // Recupera média e total atualizados
        $media = Avaliacao::where('artigo_id', $request->artigo_id)->avg('nota');
        $total = Avaliacao::where('artigo_id', $request->artigo_id)->count();

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Avaliação registrada com sucesso!',
                'media' => number_format($media, 2, ',', '.'),
                'total' => $total,
            ]);
        }

        return redirect()->back()->with('success_'.$request->artigo_id, 'Avaliação registrada com sucesso!');
    }
}
