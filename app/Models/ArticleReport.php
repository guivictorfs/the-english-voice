<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class ArticleReport extends Model
{
    protected $fillable = ['article_id', 'user_id', 'motivo'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
