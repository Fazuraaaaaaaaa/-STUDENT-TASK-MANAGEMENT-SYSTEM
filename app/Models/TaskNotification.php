<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskNotification extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'message', 'related_type', 'related_id', 'read_at'];
    protected function casts(): array { return ['read_at' => 'datetime']; }
    public function user() { return $this->belongsTo(User::class); }
}
