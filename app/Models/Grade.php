<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['submission_id', 'graded_by', 'score', 'feedback', 'published_at'];
    protected function casts(): array { return ['published_at' => 'datetime']; }
    public function submission() { return $this->belongsTo(AssignmentSubmission::class, 'submission_id'); }
    public function grader() { return $this->belongsTo(User::class, 'graded_by'); }
    public function histories() { return $this->hasMany(GradeHistory::class); }
}
