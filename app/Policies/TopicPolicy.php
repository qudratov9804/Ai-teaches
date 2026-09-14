<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * A topic is visible to whoever can view its parent subject.
     */
    public function view(?User $user, Topic $topic): bool
    {
        return (new SubjectPolicy)->view($user, $topic->subject);
    }

    /**
     * Determine whether the user can create a topic under the given subject.
     */
    public function create(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Topic $topic): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $topic->subject->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Topic $topic): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $topic->subject->teacher_id === $user->id);
    }
}
