<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use Smalot\PdfParser\Parser;
use App\Models\ForbiddenWord;

class ArtigoController extends Controller
{
    /**
     * Lista artigos denunciados (status 'Pendente') para revisão
     */
    public function pendentes()
    {
        $articles = Article::with(['authors'])
            ->where('status', 'Pendente')
            ->orderByDesc('denuncias')
            ->orderByDesc('created_at')
            ->get();
        return view('admin.artigos_pendentes', compact('articles'));
    }

    /**
     * Aprova artigo denunciado (status volta para 'Aprovado' e zera denúncias)
     */
    public function aprovar($article_id)
    {
        $article = Article::findOrFail($article_id);
        $article->status = 'Aprovado';
        $article->denuncias = 0;
        $article->save();
        return redirect()->route('admin.artigos.pendentes')->with('success', 'Artigo aprovado com sucesso!');
    }

    /**
     * Exclui artigo denunciado
     */
    public function excluir($article_id)
    {
        $article = Article::findOrFail($article_id);
        $article->delete();
        return redirect()->route('admin.artigos.pendentes')->with('success', 'Artigo excluído com sucesso!');
    }

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
                $palavrasProibidas = ForbiddenWord::pluck('word')->toArray();
        
                foreach ($palavrasProibidas as $palavra) {
                    if (stripos($text, $palavra) !== false) {
                        Storage::disk('public')->delete($pdfPath);
                        return back()->withErrors(['O PDF contém palavras inadequadas.']);
                    }
                }
        
            } catch (\Exception $e) {
                return back()->withErrors(['Erro ao processar o PDF: ' . $e->getMessage()]);
            }
        
            $artigo = Article::create([
                'title' => $request->titulo,
                'status' => 'Aprovado',
                'denuncias' => 0,
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

            $artigo = Article::create([
                'title' => $request->titulo,
                'content' => $request->conteudo,
                'status' => 'Aprovado',
                'denuncias' => 0,
            ]);
        }

        // Relaciona autores
        foreach ($autores as $i => $autorId) {
            $tipoAutor = $i === 0 ? 'Principal' : 'Secundário'; // Mapear 'Coautor' para 'Secundário'
            DB::table('article_author')->insert([
                'article_id' => $artigo->article_id,
                'id' => $autorId,
                'author_type' => $tipoAutor,
                'created_at' => now(),
            ]);
        }

        // Relaciona keywords
        foreach ($keywordsArr as $kw) {
            if (!$kw) continue;
            $kw = trim($kw);
            $keyword = \App\Models\Keyword::firstOrCreate(['name' => $kw]);
            DB::table('article_keyword')->updateOrInsert([
                'article_id' => $artigo->article_id,
                'keyword_id' => $keyword->keyword_id
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

    /**
     * Denunciar artigo: incrementa denuncias e coloca status 'Pendente' se chegar a 5
     */
    public function denunciar($article_id)
    {
        $article = Article::findOrFail($article_id);
        $article->denuncias = $article->denuncias + 1;
        if ($article->denuncias >= 5) {
            $article->status = 'Pendente';
        }
        $article->save();
        return redirect()->back()->with('success', 'Artigo denunciado com sucesso!');
    }
}
