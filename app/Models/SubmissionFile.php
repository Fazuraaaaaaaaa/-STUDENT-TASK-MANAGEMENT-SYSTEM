<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    protected $fillable = ['submission_id', 'file_path', 'original_filename', 'mime_type', 'file_size', 'version_number'];
    public function submission() { return $this->belongsTo(AssignmentSubmission::class, 'submission_id'); }
}
