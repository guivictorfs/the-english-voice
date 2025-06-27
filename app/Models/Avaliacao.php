<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';
    protected $fillable = ['user_id', 'artigo_id', 'nota'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function artigo() {
        return $this->belongsTo(Article::class, 'artigo_id', 'article_id');
    }
}
