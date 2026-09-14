<?php

namespace App\Policies;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\User;

class CurriculumPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * Curriculum documents are only visible to the owning teacher and
     * admins for now; broader (student/public) access will be designed
     * in a later stage alongside the public catalog.
     */
    public function view(User $user, Curriculum $curriculum): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $curriculum->subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can upload a curriculum document for the given subject.
     */
    public function create(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Curriculum $curriculum): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $curriculum->subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Curriculum $curriculum): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $curriculum->subject->teacher_id === $user->id);
    }
}
