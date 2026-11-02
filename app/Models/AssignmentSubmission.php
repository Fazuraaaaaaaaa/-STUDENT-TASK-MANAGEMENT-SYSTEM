<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id', 'student_id', 'notes', 'status', 'submitted_at'];
    protected function casts(): array { return ['submitted_at' => 'datetime']; }
    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function files() { return $this->hasMany(SubmissionFile::class, 'submission_id'); }
    public function grade() { return $this->hasOne(Grade::class, 'submission_id'); }
}
