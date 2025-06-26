<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = 'keyword';
    protected $primaryKey = 'keyword_id';
    public $timestamps = true;
    protected $fillable = ['name'];
}
