<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase1SmokeTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $roleSlug, ?Group $group = null): User
    {
        $role = Role::firstOrCreate(['slug' => $roleSlug], ['name' => $roleSlug]);

        return User::factory()->create([
            'role_id' => $role->id,
            'group_id' => $group?->id,
        ]);
    }

    public function test_landing_page_loads_for_guests(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_open_subjects_list_loads_for_guests(): void
    {
        $this->get('/subjects')->assertOk();
    }

    public function test_guest_is_redirected_away_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_dashboard_is_only_reachable_by_admins(): void
    {
        $admin = $this->makeUser(Role::ADMIN);
        $teacher = $this->makeUser(Role::TEACHER);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($teacher)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_teacher_can_only_manage_their_own_subject(): void
    {
        $teacher = $this->makeUser(Role::TEACHER);
        $otherTeacher = $this->makeUser(Role::TEACHER);

        $subject = Subject::create([
            'name' => 'Test fan',
            'code' => 'TST-'.uniqid(),
            'teacher_id' => $teacher->id,
            'is_open' => false,
            'is_active' => true,
        ]);

        $this->assertTrue($teacher->can('update', $subject));
        $this->assertFalse($otherTeacher->can('update', $subject));
    }

    public function test_student_only_sees_their_assigned_subjects_on_the_dashboard(): void
    {
        $group = Group::create(['name' => 'Test group', 'code' => 'TG-'.uniqid()]);
        $student = $this->makeUser(Role::STUDENT, $group);
        $otherStudent = $this->makeUser(Role::STUDENT, $group);
        $teacher = $this->makeUser(Role::TEACHER);

        $subject = Subject::create([
            'name' => 'Biriktirilgan fan',
            'code' => 'BF-'.uniqid(),
            'teacher_id' => $teacher->id,
            'is_open' => false,
            'is_active' => true,
        ]);

        $subject->students()->attach($student->id);

        $response = $this->actingAs($student)->get('/student/dashboard');
        $response->assertOk();
        $response->assertSee('Biriktirilgan fan');

        // A closed subject the other student is NOT enrolled in must not be viewable by them.
        $this->actingAs($otherStudent)->get('/subjects/'.$subject->id)->assertForbidden();
    }
}
