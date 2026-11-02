<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function viewAny(User $user): bool { return $user->isTeacher() || $user->isStudent(); }
    public function view(User $user, Assignment $assignment): bool
    {
        return $user->isTeacher()
            ? $assignment->teacher_id === $user->id
            : $assignment->status === 'published' && $assignment->class->students()->whereKey($user->id)->exists();
    }
    public function create(User $user): bool { return $user->isTeacher(); }
    public function update(User $user, Assignment $assignment): bool { return $user->isTeacher() && $assignment->teacher_id === $user->id; }
    public function delete(User $user, Assignment $assignment): bool { return $this->update($user, $assignment); }
    public function restore(User $user, Assignment $assignment): bool { return $this->update($user, $assignment); }
    public function forceDelete(User $user, Assignment $assignment): bool { return false; }
}

