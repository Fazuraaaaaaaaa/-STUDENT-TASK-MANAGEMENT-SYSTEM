<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;
    protected $fillable = ['teacher_id', 'class_id', 'subject_id', 'title', 'description', 'instructions', 'attachment_path', 'attachment_name', 'published_at', 'deadline_at', 'max_score', 'allow_late_submission', 'allow_resubmission', 'status', 'notes'];
    protected function casts(): array { return ['published_at' => 'datetime', 'deadline_at' => 'datetime', 'allow_late_submission' => 'boolean', 'allow_resubmission' => 'boolean']; }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }
    public function isOverdue(): bool { return now()->isAfter($this->deadline_at); }
}
