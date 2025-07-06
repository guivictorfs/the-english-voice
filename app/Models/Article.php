<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'article'; // Nome correto da tabela
    protected $primaryKey = 'article_id';
    protected $fillable = ['title', 'content', 'status', 'denuncias', 'approved_by', 'average_rating'];

    // Relacionamento com keywords/tags
    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword', 'article_id', 'keyword_id');
    }

    // Relacionamento com o(s) autor(es)
    public function authors()
    {
        // Ajustado para refletir a estrutura real da tabela article_author
        return $this->belongsToMany(User::class, 'article_author', 'article_id', 'id')
            ->withPivot('author_type', 'created_at');
    }

    /**
     * Usuários que favoritaram este artigo
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite', 'article_id', 'user_id')->withTimestamps();
    }

    /**
     * Avaliações deste artigo
     */
    public function avaliacoes()
    {
        return $this->hasMany(\App\Models\Avaliacao::class, 'artigo_id', 'article_id');
    }

    /**
     * Comentários deste artigo
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'article_id', 'article_id');
    }
}
