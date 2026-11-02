<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    protected $fillable = ['grade_id', 'previous_score', 'new_score', 'previous_feedback', 'new_feedback', 'changed_by'];
    public function grade() { return $this->belongsTo(Grade::class); }
    public function changer() { return $this->belongsTo(User::class, 'changed_by'); }
}
