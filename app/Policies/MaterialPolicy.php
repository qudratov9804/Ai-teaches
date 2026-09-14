<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\Subject;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * Materials are only visible to the owning teacher and admins for
     * now; broader (student/public) access will be designed in a later
     * stage alongside the public catalog.
     */
    public function view(User $user, Material $material): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $material->subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can upload a material for the given subject.
     */
    public function create(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Material $material): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $material->subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Material $material): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $material->subject->teacher_id === $user->id);
    }
}
