<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    public function view(User $user, Grade $grade): bool
    {
        return $user->id === $grade->submission->student_id || $user->id === $grade->submission->assignment->teacher_id;
    }

    public function create(User $user, Grade $grade): bool
    {
        return $user->isTeacher() && $user->id === $grade->submission->assignment->teacher_id;
    }

    public function update(User $user, Grade $grade): bool
    {
        return $user->isTeacher() && $user->id === $grade->graded_by && $user->id === $grade->submission->assignment->teacher_id;
    }

    public function delete(User $user, Grade $grade): bool
    {
        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->isTeacher() || $user->isStudent();
    }

    public function restore(User $user, Grade $grade): bool
    {
        return false;
    }

    public function forceDelete(User $user, Grade $grade): bool
    {
        return false;
    }
}

