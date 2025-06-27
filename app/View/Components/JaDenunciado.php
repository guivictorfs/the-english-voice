<?php
namespace App\View\Components;

use Illuminate\View\Component;

class JaDenunciado extends Component
{
    public $jaDenunciado;
    public function __construct($articleId)
    {
        $this->jaDenunciado = false;
        if (auth()->check()) {
            $this->jaDenunciado = \App\Models\ArticleReport::where('user_id', auth()->id())
                ->where('article_id', $articleId)
                ->exists();
        }
    }
    public function render()
    {
        return view('components.ja_denunciado');
    }
}
