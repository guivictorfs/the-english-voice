<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspiciousActivity extends Model
{
    protected $table = 'suspicious_activities';
    protected $fillable = ['user_id', 'type', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
