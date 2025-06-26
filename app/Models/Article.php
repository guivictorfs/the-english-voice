<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'article'; // Nome correto da tabela
    protected $primaryKey = 'article_id';
    protected $fillable = ['title', 'status', 'approved_by', 'average_rating'];

    // Relacionamento com o(s) autor(es)
    public function authors()
    {
        // Ajustado para refletir a estrutura real da tabela article_author
        return $this->belongsToMany(User::class, 'article_author', 'article_id', 'id')
            ->withPivot('author_type', 'created_at');
    }
}
