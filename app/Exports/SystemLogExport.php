<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\SystemLog;
use App\Models\ArticleHistory;
use App\Models\Comment;

class SystemLogExport implements WithMultipleSheets
{
    protected $logs;
    protected $articleHistory;
    protected $comments;

    public function __construct($logs, $articleHistory, $comments)
    {
        $this->logs = $logs;
        $this->articleHistory = $articleHistory;
        $this->comments = $comments;
    }

    public function sheets(): array
    {
        return [
            new SystemLogSheet($this->logs),
            new ArticleHistorySheet($this->articleHistory),
            new CommentHistorySheet($this->comments)
        ];
    }
}

class SystemLogSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return $this->logs->map(function ($log) {
            return [
                $log->record_id,
                $log->created_at->format('d/m/Y H:i'),
                $log->action,
                $log->user->name ?? 'Usuário não encontrado',
                $log->user->email ?? 'Email não encontrado',
                $log->description
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Registro',
            'Data/Hora',
            'Ação',
            'Usuário',
            'Email',
            'Descrição'
        ];
    }

    public function title(): string
    {
        return 'Logs do Sistema';
    }
}

class ArticleHistorySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $articleHistory;

    public function __construct($articleHistory)
    {
        $this->articleHistory = $articleHistory;
    }

    public function collection()
    {
        return $this->articleHistory->map(function ($history) {
            return [
                $history->article_id,
                $history->created_at->format('d/m/Y H:i'),
                $history->user->name ?? 'Usuário não encontrado',
                $history->change_type,
                $history->change_description
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Artigo',
            'Data',
            'Usuário',
            'Tipo de Alteração',
            'Descrição'
        ];
    }

    public function title(): string
    {
        return 'Histórico de Artigos';
    }
}

class CommentHistorySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $comments;

    public function __construct($comments)
    {
        $this->comments = $comments;
    }

    public function collection()
    {
        return $this->comments->map(function ($comment) {
            return [
                $comment->id,
                $comment->updated_at->format('d/m/Y H:i'),
                $comment->user->name ?? 'Usuário não encontrado',
                $comment->content
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Comentário',
            'Data',
            'Usuário',
            'Conteúdo'
        ];
    }

    public function title(): string
    {
        return 'Histórico de Comentários';
    }
}
