<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = ['teacher_id', 'name', 'class_code', 'academic_year', 'description', 'invitation_enabled', 'invitation_code'];

    protected function casts(): array
    {
        return ['invitation_enabled' => 'boolean'];
    }

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function students() { return $this->belongsToMany(User::class, 'class_students', 'class_id', 'student_id')->withPivot('joined_at')->withTimestamps(); }
    public function subjects() { return $this->hasMany(Subject::class, 'class_id'); }
    public function assignments() { return $this->hasMany(Assignment::class, 'class_id'); }
} 
