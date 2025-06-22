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
    public function author()
    {
        // Ajuste conforme sua estrutura de relacionamento
        return $this->belongsToMany(User::class, 'article_author', 'article_id', 'user_id');
    }
}
