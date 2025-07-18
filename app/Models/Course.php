<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course';
    protected $primaryKey = 'course_id';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'int';

    // Campos preenchíveis
    protected $fillable = ['course_name'];

}
