<?php

namespace Tests\Concerns;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;

trait CreatesUsers
{
    protected function admin(): User
    {
        return $this->userWithRole(Role::ADMIN);
    }

    protected function teacher(): User
    {
        return $this->userWithRole(Role::TEACHER);
    }

    protected function student(?Group $group = null): User
    {
        return $this->userWithRole(Role::STUDENT, $group);
    }

    private function userWithRole(string $roleSlug, ?Group $group = null): User
    {
        $role = Role::firstOrCreate(['slug' => $roleSlug], ['name' => $roleSlug]);

        return User::factory()->create([
            'role_id' => $role->id,
            'group_id' => $group?->id,
        ]);
    }
}
