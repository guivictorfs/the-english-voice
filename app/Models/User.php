<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'ra',
        'course_id',
    ];

    /**
     * Atributos que devem ser ocultados na serialização.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relacionamento com o curso (pertence a).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Retorna a cor do badge baseado no role
     *
     * @return string
     */
    public function getBadgeColor()
    {
        switch ($this->role) {
            case 'Admin':
                return 'bg-danger';
            case 'Professor':
                return 'bg-warning';
            default:
                return 'bg-success';
        }
    }

    /**
     * Artigos favoritados pelo usuário
     */
    public function favorites()
    {
        return $this->belongsToMany(\App\Models\Article::class, 'favorite', 'user_id', 'article_id')->withTimestamps();
    }
}
