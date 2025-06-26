<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Artigo;
use Smalot\PdfParser\Parser;

class ArtigoController extends Controller
{
    public function store(Request $request)
    {
        $tipo = $request->input('tipo_formulario');
        $autores = $request->input('autores', []);
        $keywordsInput = $request->input('keywords', '');
        $keywordsArr = [];

        // Processa keywords
        if ($keywordsInput) {
            if (is_array($keywordsInput)) {
                $keywordsArr = $keywordsInput;
            } else {
                $decoded = json_decode($keywordsInput, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $kw) {
                        if (is_array($kw) && isset($kw['value'])) $keywordsArr[] = trim($kw['value']);
                        elseif (is_string($kw)) $keywordsArr[] = trim($kw);
                    }
                } else {
                    $keywordsArr = array_map('trim', explode(',', $keywordsInput));
                }
            }
        }

        // Envio via PDF (sem validação de conteúdo)
        if ($tipo === 'pdf') {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'pdf' => 'required|mimes:pdf|max:5120',
                'autores' => 'required|array|min:1',
            ]);
        
            $pdfPath = $request->file('pdf')->store('artigos', 'public');
            $fileName = $request->file('pdf')->getClientOriginalName();
            $pdfFullPath = storage_path('app/public/' . $pdfPath);
        
            // Extrai o texto do PDF
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($pdfFullPath);
                $text = $pdf->getText();
        
                // Lista simples de palavras proibidas
                $palavrasPath = storage_path('app/palavras_proibidas.txt');

                if (file_exists($palavrasPath)) {
                    $palavrasProibidas = array_map('trim', file($palavrasPath));
                } else {
                    $palavrasProibidas = []; // Se não existir, não bloqueia nada
                }
        
                foreach ($palavrasProibidas as $palavra) {
                    if (stripos($text, $palavra) !== false) {
                        Storage::disk('public')->delete($pdfPath);
                        return back()->withErrors(['O PDF contém palavras inadequadas.']);
                    }
                }
        
            } catch (\Exception $e) {
                return back()->withErrors(['Erro ao processar o PDF: ' . $e->getMessage()]);
            }
        
            $artigo = Artigo::create([
                'title' => $request->titulo,
            ]);
        
            DB::table('file_upload')->insert([
                'article_id' => $artigo->article_id,
                'file_name' => $fileName,
                'file_path' => $pdfPath,
                'uploaded_by' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        // Envio via formulário de texto
        if ($tipo === 'escrever') {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'conteudo' => 'required|string',
                'autores' => 'required|array|min:1',
            ]);

            $artigo = Artigo::create([
                'title' => $request->titulo,
                'content' => $request->conteudo,
            ]);
        }

        // Relaciona autores
        foreach ($autores as $i => $autorId) {
            DB::table('article_author')->insert([
                'article_id' => $artigo->article_id,
                'id' => $autorId,
                'author_type' => $i === 0 ? 'Principal' : 'Coautor',
                'created_at' => now(),
            ]);
        }

        // Relaciona keywords
        foreach ($keywordsArr as $kw) {
            if (!$kw) continue;
            $keywordId = DB::table('keyword')->where('name', $kw)->value('keyword_id');
            if (!$keywordId) {
                $keywordId = DB::table('keyword')->insertGetId([
                    'name' => $kw,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            DB::table('article_keyword')->updateOrInsert([
                'article_id' => $artigo->article_id,
                'keyword_id' => $keywordId
            ], [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('artigos.postar')->with('success', 'Artigo enviado com sucesso!');
    }

    public function visualizar($article_id)
    {
        $artigo = \App\Models\Article::findOrFail($article_id);

        $file = DB::table('file_upload')
            ->join('article_author', function ($join) use ($article_id) {
                $join->on('file_upload.uploaded_by', '=', 'article_author.id')
                    ->where('article_author.article_id', '=', $article_id);
            })
            ->orderByDesc('file_upload.created_at')
            ->first();

        return view('artigo_visualizar', [
            'artigo' => $artigo,
            'pdfPath' => $file ? $file->file_path : null
        ]);
    }
}
