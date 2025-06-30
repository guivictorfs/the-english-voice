<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Ou usar o facade diretamente com \Log::

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use Smalot\PdfParser\Parser;
use App\Models\ForbiddenWord;
use App\Notifications\ArtigoDenunciadoNotification;
use App\Models\User;
use App\Models\ArticleReport;
use Barryvdh\DomPDF\Facade\Pdf; // PDF facade
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArtigoController extends Controller
{
    /**
     * Exibe a lista de artigos favoritos com filtros
     */
    public function favorites()
    {
        $user = auth()->user();
        
        $query = Article::with(['authors', 'keywords'])
            ->whereHas('favorites', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filtro por título ou conteúdo
        if (request('q')) {
            $searchTerm = request('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por tags
        if (request('tags')) {
            $tags = request('tags');
            if (is_string($tags)) {
                $tags = [$tags];
            }
            $query->whereHas('keywords', function($q) use ($tags) {
                $q->whereIn('keyword', $tags);
            });
        }

        // Filtro por autor
        if (request('author')) {
            $author = request('author');
            $query->whereHas('authors', function($q) use ($author) {
                $q->where('name', 'like', '%' . $author . '%');
            });
        }

        $favorites = $query->paginate(10);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Exibe um artigo individual
     */
    public function show($article_id)
    {
        $article = \App\Models\Article::with(['authors', 'keywords'])->findOrFail($article_id);
        // Calcule média e total de avaliações
        $media = \App\Models\Avaliacao::where('artigo_id', $article->article_id)->avg('nota');
        $total = \App\Models\Avaliacao::where('artigo_id', $article->article_id)->count();
        $article->media_avaliacoes = $media ? round($media, 2) : null;
        $article->total_avaliacoes = $total;
        // Nota do usuário logado (se houver)
        $notaUsuario = null;
        if (auth()->check()) {
            $avaliacao = \App\Models\Avaliacao::where('artigo_id', $article->article_id)
                ->where('user_id', auth()->id())
                ->first();
            if ($avaliacao) {
                $notaUsuario = $avaliacao->nota;
            }
        }
        $ultimaDenuncia = \App\Models\ArticleReport::where('article_id', $article->article_id)
    ->where('user_id', auth()->id())
    ->latest('created_at')
    ->first();
$jaDenunciou = $ultimaDenuncia && $ultimaDenuncia->created_at > $article->updated_at;
return view('artigos.show', compact('article', 'notaUsuario', 'jaDenunciou'));
    }
    /**
     * Exibe o formulário de edição do artigo
     */
    public function edit($id)
    {
        $article = \App\Models\Article::with('authors')->findOrFail($id);
        $user = auth()->user();
        $isAuthor = $article->authors->contains('id', $user->id);
        $isProfessorOrAdmin = in_array($user->role, ['Professor', 'admin']);
        if (!$isAuthor && !$isProfessorOrAdmin) abort(403);
        
        // Verifica se é um artigo PDF
        $pdf = DB::table('file_upload')
            ->where('article_id', $article->article_id)
            ->orderByDesc('created_at')
            ->first();
        
        if ($pdf && $pdf->file_path) {
            $article->is_pdf = true;
            $article->pdf_path = $pdf->file_path;
        }
        
        return view('artigos.edit', compact('article'));
    }

    /**
     * Processa a edição do artigo
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $article = \App\Models\Article::with('authors')->findOrFail($id);
        $user = auth()->user();
        $isAuthor = $article->authors->contains('id', $user->id);
        $isProfessorOrAdmin = in_array($user->role, ['Professor', 'admin']);
        if (!$isAuthor && !$isProfessorOrAdmin) abort(403);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
        ]);
        $oldContent = $article->content;
        $oldTitle = $article->title;

        // Validação de palavras proibidas
        $forbiddenWords = \App\Models\ForbiddenWord::pluck('word')->toArray();
        $foundWords = [];
        foreach ($forbiddenWords as $fw) {
            if (stripos($validated['title'], $fw) !== false || (isset($validated['content']) && stripos($validated['content'], $fw) !== false)) {
                $foundWords[] = $fw;
            }
        }
        if (count($foundWords) > 0) {
            return back()->withErrors(['O artigo contém palavras proibidas: ' . implode(', ', $foundWords)])->withInput();
        }

        // Atualiza o título e conteúdo primeiro
        $article->update($validated);

        // Debug - log das palavras-chave recebidas
        \Illuminate\Support\Facades\Log::info('Palavras-chave recebidas:', [$request->input('keywords')]);

        // Trata as palavras-chave
        $keywordsRaw = $request->input('keywords'); // deve ser uma string: "tag1, tag2, tag3"

        if ($keywordsRaw) {
            // Separa e limpa as tags
            $keywords = explode(',', $keywordsRaw);
            $keywords = array_map('trim', $keywords);
            $keywords = array_filter($keywords, function($tag) {
                return !empty($tag);
            });

            // Validação de palavras proibidas nas tags
            $forbiddenWords = \App\Models\ForbiddenWord::pluck('word')->toArray();
            $foundForbidden = [];
            
            foreach ($keywords as $tag) {
                foreach ($forbiddenWords as $fw) {
                    if (stripos($tag, $fw) !== false) {
                        $foundForbidden[] = $fw;
                    }
                }
            }

            if (!empty($foundForbidden)) {
                return back()->withErrors(['As tags contêm palavras proibidas: ' . implode(', ', $foundForbidden)])->withInput();
            }

            // Cria/Recupera as tags e coleta os IDs
            $keywordIds = [];
            foreach ($keywords as $keywordName) {
                if (!empty($keywordName)) {
                    $keyword = \App\Models\Keyword::firstOrCreate(['name' => $keywordName]);
                    $keywordIds[] = $keyword->getKey(); // Usa getKey() para pegar a chave primária correta
                }
            }

            // Remove IDs vazios ou nulos
            $keywordIds = array_filter($keywordIds, function($id) {
                return !empty($id) && is_numeric($id);
            });

            // Sincroniza as tags apenas se tivermos IDs válidos
            if (!empty($keywordIds)) {
                $article->keywords()->sync($keywordIds);
            } else {
                // Se não houver tags válidas, limpa todas
                $article->keywords()->detach();
            }
        } else {
            // Se não houver tags válidas, limpa todas
            $article->keywords()->detach();
        }

        // Zera denúncias ao editar
        $article->denuncias = 0;
        $article->status = 'Aprovado'; // Opcional: volta para aprovado automaticamente ao editar
        $article->save();

        // Salva histórico
        DB::table('article_history')->insert([
            'article_id' => $article->article_id,
            'changed_by' => $user->id,
            'change_type' => 'Edição',
            'change_description' => 'Título anterior: ' . $oldTitle . ' | Conteúdo anterior: ' . ($oldContent ?? ''),
            'created_at' => now()
        ]);

        return redirect()->route('dashboard')->with('success', 'Artigo editado com sucesso!');
    }
    /**
     * Lista artigos denunciados (status 'Pendente') para revisão
     */
    public function pendentes()
    {
        $articles = Article::with(['authors'])
            ->where('denuncias', '>=', 1)
            ->orderByDesc('denuncias')
            ->orderByDesc('created_at')
            ->get();
        // Buscar denúncias agrupadas por artigo
        $reports = \App\Models\ArticleReport::with('user')->get()->groupBy('article_id');
        return view('admin.artigos_pendentes', compact('articles', 'reports'));
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
            // Se for JSON, decodifica
            if (is_string($keywordsInput) && strpos($keywordsInput, '[') === 0) {
                $decoded = json_decode($keywordsInput, true);
                if (is_array($decoded)) {
                    $keywordsArr = array_map('trim', $decoded);
                }
            } else {
                // Se for string com vírgulas, separa
                $keywordsArr = array_map('trim', explode(',', $keywordsInput));
            }

            // Remove tags vazias
            $keywordsArr = array_filter($keywordsArr, function($tag) {
                return !empty($tag);
            });
        }

        // Validação de palavras proibidas no título, conteúdo e keywords
        $forbiddenWords = \App\Models\ForbiddenWord::pluck('word')->toArray();
        $foundWords = [];
        $titulo = $request->input('titulo', '');
        $conteudo = $request->input('conteudo', '');
        foreach ($forbiddenWords as $fw) {
            if (stripos($titulo, $fw) !== false || stripos($conteudo, $fw) !== false) {
                $foundWords[] = $fw;
            } else {
                foreach ($keywordsArr as $kw) {
                    if (stripos($kw, $fw) !== false) {
                        $foundWords[] = $fw;
                    }
                }
            }
        }
        if (count($foundWords) > 0) {
            return back()->withErrors(['O artigo contém palavras proibidas: ' . implode(', ', array_unique($foundWords))])->withInput();
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

        // Notifica autores do artigo
        $usuarios = \App\Models\User::whereIn('id', $autores)->get();
        foreach ($usuarios as $usuario) {
            $usuario->notify(new \App\Notifications\ArtigoPostadoNotification($artigo->title));
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
        $artigo = \App\Models\Article::with(['authors', 'keywords'])->findOrFail($article_id);

        $file = DB::table('file_upload')
            ->where('article_id', $article_id)
            ->orderByDesc('created_at')
            ->first();

        return view('artigo_visualizar', [
            'artigo' => $artigo,
            'pdfPath' => $file ? $file->file_path : null
        ]);
    }

    /**
     * Denunciar artigo: incrementa denuncias e coloca status 'Pendente' se chegar a 5
     */
    public function denunciar(Request $request, $article_id)
    {
        $article = Article::findOrFail($article_id);
        $motivo = $request->input('motivo', 'Motivo não informado');
        $userId = Auth::id();
        // Checa se já existe denúncia desse aluno para esse artigo
        $jaDenunciou = ArticleReport::where('article_id', $article_id)->where('user_id', $userId)->exists();
        if ($jaDenunciou) {
            return redirect()->back()->withErrors(['Você já denunciou este artigo.']);
        }
        // Salva a denúncia
        ArticleReport::create([
            'article_id' => $article_id,
            'user_id' => $userId,
            'motivo' => $motivo,
        ]);
        // Incrementa contador e notifica autores
        $article->denuncias = $article->denuncias + 1;
        if ($article->denuncias >= 5) {
            $article->status = 'Pendente';
        }
        $article->save();
        $autorIds = DB::table('article_author')->where('article_id', $article_id)->pluck('id');
        $autores = User::whereIn('id', $autorIds)->get();
        foreach ($autores as $autor) {
            $autor->notify(new ArtigoDenunciadoNotification($article->title, $motivo, $article->article_id));
        }
        return redirect()->back()->with('success', 'Artigo denunciado com sucesso!');
    }

    /**
     * Gera PDF do artigo (título, autores, corpo)
     */
    public function gerarPdf($article_id)
    {
        $artigo = Article::with('authors')->findOrFail($article_id);
        $pdf = Pdf::loadView('artigos.pdf', ['artigo' => $artigo]);
        $titulo = trim($artigo->title) ? str_replace(' ', '_', Str::slug(substr($artigo->title, 0, 50))) : 'artigo_'.$artigo->article_id;
        return $pdf->download($titulo.'.pdf');
    }

    /**
     * Exibe os artigos com melhor avaliação
     */
    public function melhores()
    {
        $now = now();
        // Artigos com melhor avaliação geral
        $artigos = \App\Models\Article::with(['authors', 'keywords'])
            ->where('status', 'Aprovado')
            ->withAvg('avaliacoes', 'nota')
            ->withCount('avaliacoes')
            ->orderByDesc('avaliacoes_avg_nota')
            ->orderByDesc('avaliacoes_count')
            ->take(20)
            ->get();

        // Média geral do site
        $mediaGeral = \App\Models\Avaliacao::avg('nota');
        $nMinimo = 5;

        // Melhores notas no mês (mínimo 5 avaliações)
        $maisAvaliadosMes = \App\Models\Article::with(['authors', 'keywords'])
            ->where('status', 'Aprovado')
            ->whereHas('avaliacoes', function($q) use ($now) {
                $q->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            })
            ->withCount(['avaliacoes as avaliacoes_mes_count' => function($q) use ($now) {
                $q->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            }])
            ->withAvg(['avaliacoes as media_mes' => function($q) use ($now) {
                $q->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            }], 'nota')
            ->having('avaliacoes_mes_count', '>=', $nMinimo)
            ->get();
        foreach ($maisAvaliadosMes as $artigo) {
            $n = $artigo->avaliacoes_mes_count;
            $media = $artigo->media_mes;
            $artigo->media_ponderada_mes = ($media * $n + $mediaGeral * $nMinimo) / ($n + $nMinimo);
        }
        $maisAvaliadosMes = $maisAvaliadosMes->sortByDesc('media_ponderada_mes')->take(10);

        // Melhores notas no ano (mínimo 5 avaliações)
        $maisAvaliadosAno = \App\Models\Article::with(['authors', 'keywords'])
            ->where('status', 'Aprovado')
            ->whereHas('avaliacoes', function($q) use ($now) {
                $q->whereYear('created_at', $now->year);
            })
            ->withCount(['avaliacoes as avaliacoes_ano_count' => function($q) use ($now) {
                $q->whereYear('created_at', $now->year);
            }])
            ->withAvg(['avaliacoes as media_ano' => function($q) use ($now) {
                $q->whereYear('created_at', $now->year);
            }], 'nota')
            ->having('avaliacoes_ano_count', '>=', $nMinimo)
            ->get();
        foreach ($maisAvaliadosAno as $artigo) {
            $n = $artigo->avaliacoes_ano_count;
            $media = $artigo->media_ano;
            $artigo->media_ponderada_ano = ($media * $n + $mediaGeral * $nMinimo) / ($n + $nMinimo);
        }
        $maisAvaliadosAno = $maisAvaliadosAno->sortByDesc('media_ponderada_ano')->take(10);

        // Melhores notas no geral (mínimo 5 avaliações)
        $maisAvaliadosGeral = \App\Models\Article::with(['authors', 'keywords'])
            ->where('status', 'Aprovado')
            ->withCount('avaliacoes')
            ->withAvg('avaliacoes', 'nota')
            ->having('avaliacoes_count', '>=', $nMinimo)
            ->get();
        foreach ($maisAvaliadosGeral as $artigo) {
            $n = $artigo->avaliacoes_count;
            $media = $artigo->avaliacoes_avg_nota;
            $artigo->media_ponderada = ($media * $n + $mediaGeral * $nMinimo) / ($n + $nMinimo);
        }
        $maisAvaliadosGeral = $maisAvaliadosGeral->sortByDesc('media_ponderada')->take(10);


        return view('artigos.melhores', compact('artigos', 'maisAvaliadosMes', 'maisAvaliadosAno', 'maisAvaliadosGeral'));
    }
}
