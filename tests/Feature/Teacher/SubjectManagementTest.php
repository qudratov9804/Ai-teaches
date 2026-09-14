<?php

namespace Tests\Feature\Teacher;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class SubjectManagementTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_teacher_can_view_their_own_subject_management_page(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)
            ->get(route('teacher.subjects.show', $subject))
            ->assertOk()
            ->assertSee($subject->name);
    }

    public function test_teacher_dashboard_shows_stats_and_own_subjects_only(): void
    {
        $teacher = $this->teacher();
        $other = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create(['name' => 'Mening fanim']);
        Subject::factory()->for($other, 'teacher')->create(['name' => 'Begona fan']);

        $this->actingAs($teacher)
            ->get(route('teacher.dashboard'))
            ->assertOk()
            ->assertSee('Mening fanim')
            ->assertDontSee('Begona fan');
    }

    public function test_teacher_can_update_their_own_subject(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create(['name' => 'Eski nom']);

        $response = $this->actingAs($teacher)->put(route('teacher.subjects.update', $subject), [
            'name' => 'Yangilangan fan nomi',
            'code' => $subject->code,
            'description' => 'Yangi tavsif',
            'course' => 2,
            'semester' => 4,
            'credit' => 6,
            'is_open' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('teacher.subjects.show', $subject));
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Yangilangan fan nomi',
            'is_open' => true,
        ]);
    }

    public function test_updating_a_subject_validates_required_fields(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)
            ->put(route('teacher.subjects.update', $subject), ['name' => '', 'code' => ''])
            ->assertSessionHasErrors(['name', 'code']);
    }
}
