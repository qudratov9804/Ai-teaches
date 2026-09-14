<?php

namespace Tests\Feature\Teacher;

use App\Models\Curriculum;
use App\Models\Group;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_guest_cannot_reach_the_teacher_dashboard(): void
    {
        $this->get(route('teacher.dashboard'))->assertRedirect(route('login'));
    }

    public function test_teacher_cannot_view_another_teachers_subject_management_page(): void
    {
        $owner = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($owner, 'teacher')->create();

        $this->actingAs($other)
            ->get(route('teacher.subjects.show', $subject))
            ->assertForbidden();
    }

    public function test_teacher_cannot_update_another_teachers_subject(): void
    {
        $owner = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($owner, 'teacher')->create();

        $this->actingAs($other)->put(route('teacher.subjects.update', $subject), [
            'name' => 'Bosqinchilik',
            'code' => $subject->code,
        ])->assertForbidden();
    }

    public function test_teacher_cannot_manage_topics_of_another_teachers_subject(): void
    {
        $owner = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($owner, 'teacher')->create();
        $topic = Topic::factory()->for($subject)->create();

        $this->actingAs($other)
            ->post(route('teacher.topics.store', $subject), ['name' => 'Bosqinchilik'])
            ->assertForbidden();

        $this->actingAs($other)
            ->delete(route('teacher.topics.destroy', [$subject, $topic]))
            ->assertForbidden();
    }

    public function test_teacher_cannot_delete_another_teachers_material(): void
    {
        $owner = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($owner, 'teacher')->create();
        $material = Material::factory()->for($subject)->create(['uploaded_by' => $owner->id]);

        $this->actingAs($other)
            ->delete(route('teacher.materials.destroy', $material))
            ->assertForbidden();

        $this->assertDatabaseHas('materials', ['id' => $material->id, 'deleted_at' => null]);
    }

    public function test_teacher_cannot_download_another_teachers_curriculum(): void
    {
        $owner = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($owner, 'teacher')->create();
        $curriculum = Curriculum::factory()->for($subject)->create(['uploaded_by' => $owner->id]);

        $this->actingAs($other)
            ->get(route('teacher.curriculum.download', $curriculum))
            ->assertForbidden();
    }

    public function test_student_cannot_use_teacher_routes(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $student = $this->student();

        $this->actingAs($student)
            ->get(route('teacher.dashboard'))
            ->assertForbidden();

        $this->actingAs($student)
            ->get(route('teacher.subjects.show', $subject))
            ->assertForbidden();

        $this->actingAs($student)
            ->post(route('teacher.topics.store', $subject), ['name' => 'Talaba mavzusi'])
            ->assertForbidden();
    }

    public function test_student_cannot_view_another_students_dashboard_data(): void
    {
        $group = Group::create(['name' => 'Test group', 'code' => 'TG-'.uniqid()]);
        $teacher = $this->teacher();
        $studentA = $this->student($group);
        $studentB = $this->student($group);

        $privateSubject = Subject::factory()->for($teacher, 'teacher')->create(['is_open' => false]);
        $privateSubject->students()->attach($studentA->id);

        $this->actingAs($studentB)
            ->get(route('subjects.show', $privateSubject))
            ->assertForbidden();
    }
}
