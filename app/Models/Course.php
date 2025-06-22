<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    use HasFactory;

    // Definindo o nome da tabela

    // Definindo os campos que podem ser preenchidos
    protected $fillable = ['course_name'];
}
