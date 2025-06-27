<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AvaliacaoEstrelas extends Component
{
    public $artigo;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $notaUsuario;

    public function __construct($artigo)
    {
        $this->artigo = $artigo;
        $this->notaUsuario = null;
        if (auth()->check()) {
            $avaliacao = \App\Models\Avaliacao::where('user_id', auth()->id())
                ->where('artigo_id', $artigo->article_id)
                ->first();
            if ($avaliacao) {
                $this->notaUsuario = $avaliacao->nota;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.avaliacao_estrelas');
    }
}
