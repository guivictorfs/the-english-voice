<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Artigo;
use Smalot\PdfParser\Parser;
use Profanity;

class ArtigoController extends Controller
{
    public function store(Request $request)
    {
        $tipo = $request->input('tipo_formulario');
        $autores = $request->input('autores', []); // array de user_id
        $keywordsInput = $request->input('keywords', ''); // pode ser string JSON ou CSV
        // Normaliza keywords para array
        $keywordsArr = [];
        if ($keywordsInput) {
            if (is_array($keywordsInput)) {
                $keywordsArr = $keywordsInput;
            } else {
                // Tenta decodificar JSON (Tagify envia array serializado), senão separa por vírgula
                $decoded = json_decode($keywordsInput, true);
                if (is_array($decoded)) {
                    // Tagify pode mandar array de objetos {value: 'palavra'}
                    foreach ($decoded as $kw) {
                        if (is_array($kw) && isset($kw['value'])) $keywordsArr[] = trim($kw['value']);
                        elseif (is_string($kw)) $keywordsArr[] = trim($kw);
                    }
                } else {
                    // Fallback: CSV
                    $keywordsArr = array_map('trim', explode(',', $keywordsInput));
                }
            }
        }
        // Validação automática de palavras inadequadas usando askedio/laravel5-profanity-filter
        // e validação do texto extraído do PDF com spatie/pdf-to-text
        if ($tipo === 'pdf') {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'pdf' => 'required|mimes:pdf|max:5120',
                'autores' => 'required|array|min:1',
            ]);
            $pdfPath = $request->file('pdf')->store('artigos', 'public');
            $fileName = $request->file('pdf')->getClientOriginalName();

            // Extrai texto do PDF
            $pdfFullPath = storage_path('app/public/' . $pdfPath);
            $pdfBin = config('pdf-to-text.bin_path');
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($pdfFullPath);
                $pdfText = $pdf->getText();
            } catch (\Exception $e) {
                return back()->withErrors([
                    'Erro ao extrair texto do PDF.',
                    'Caminho do PDF: ' . $pdfFullPath,
                    'Mensagem: ' . $e->getMessage()
                ]);
            }

            // Validação usando askedio/laravel5-profanity-filter
            if (Profanity::blocker()->isDirty($pdfText)) {
                // Remove o arquivo salvo
                \Storage::disk('public')->delete($pdfPath);
                return back()->withErrors(['O PDF contém palavras inadequadas.']);
            }

            $artigo = Artigo::create([
                'title' => $request->titulo,
            ]);
            \DB::table('file_upload')->insert([
                'article_id' => $artigo->article_id,
                'file_name' => $fileName,
                'file_path' => $pdfPath,
                'uploaded_by' => \Auth::id(),
                'created_at' => now(),
            ]);
        } else {
            // Validação para formulário de texto
            $request->validate([
                'titulo' => 'required|string|max:255',
                'conteudo' => 'required|string',
                'autores' => 'required|array|min:1',
            ]);
            // Validação de palavras inadequadas no título/conteúdo usando askedio
            $tituloCheck = $request->input('titulo', '');
            $conteudoCheck = $request->input('conteudo', '');
            $keywordsCheck = is_array($keywordsArr) ? implode(' ', $keywordsArr) : $keywordsArr;
            if (Profanity::blocker()->isDirty($tituloCheck) || Profanity::blocker()->isDirty($conteudoCheck) || Profanity::blocker()->isDirty($keywordsCheck)) {
                return back()->withErrors(['Seu artigo contém palavras inadequadas.']);
            }
            $artigo = Artigo::create([
                'title' => $request->titulo,
                'content' => $request->conteudo,
            ]);
        }
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
        } else {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'pdf' => 'required|mimes:pdf|max:5120',
                'autores' => 'required|array|min:1',
            ]);
            $pdfPath = $request->file('pdf')->store('artigos', 'public');
            $fileName = $request->file('pdf')->getClientOriginalName();



            $artigo = Artigo::create([
                'title' => $request->titulo,
            ]);
            \DB::table('file_upload')->insert([
                'article_id' => $artigo->article_id,
                'file_name' => $fileName,
                'file_path' => $pdfPath,
                'uploaded_by' => Auth::id(),
                'created_at' => now(),
            ]);
        }
        // Relaciona todos os autores selecionados
        foreach ($autores as $i => $autorId) {
            \DB::table('article_author')->insert([
                'article_id' => $artigo->article_id,
                'id' => $autorId,
                'author_type' => $i === 0 ? 'Principal' : 'Coautor',
                'created_at' => now(),
            ]);
        }
        // Keywords: cria se não existir, vincula ao artigo
        foreach ($keywordsArr as $kw) {
            if (!$kw) continue;
            $keywordId = \DB::table('keyword')->where('name', $kw)->value('keyword_id');
            if (!$keywordId) {
                $keywordId = \DB::table('keyword')->insertGetId([
                    'name' => $kw,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            // Vincula na pivot
            \DB::table('article_keyword')->updateOrInsert([
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
        // Busca o caminho do PDF (busca o upload mais recente do autor principal)
        $file = \DB::table('file_upload')
            ->join('article_author', function($join) use ($article_id) {
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
