<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'actor_id', 'action', 'details', 'ip_address', 'subject_type', 'subject_id'
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
