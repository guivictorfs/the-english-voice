<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'article_id';
    public $timestamps = true;
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'approved_by',
        'average_rating',
        'created_at',
        'updated_at',
    ];
}
