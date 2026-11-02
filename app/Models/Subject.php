<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['teacher_id', 'class_id', 'name', 'subject_code', 'description'];
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function assignments() { return $this->hasMany(Assignment::class); }
}
