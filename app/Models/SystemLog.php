<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_audit_log';
    protected $fillable = [
        'id',
        'record_id',
        'action',
        'table_name',
        'performed_by',
        'email',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by', 'id');
    }
}
