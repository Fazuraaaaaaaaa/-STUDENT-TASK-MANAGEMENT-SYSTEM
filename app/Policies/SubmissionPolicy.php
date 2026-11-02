<?php

namespace App\Policies;

use App\Models\AssignmentSubmission;
use App\Models\User;

class SubmissionPolicy
{
    public function view(User $user, AssignmentSubmission $submission): bool
    {
        return $user->id === $submission->student_id || $user->id === $submission->assignment->teacher_id;
    }
    public function submit(User $user, AssignmentSubmission $submission): bool
    {
        return $user->isStudent() && $user->id === $submission->student_id;
    }
    public function grade(User $user, AssignmentSubmission $submission): bool
    {
        return $user->isTeacher() && $user->id === $submission->assignment->teacher_id;
    }
}

