<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleHistory extends Model
{
    protected $table = 'article_history';
    protected $fillable = [
        'article_id',
        'changed_by',
        'change_type',
        'change_description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'article_id');
    }
}
