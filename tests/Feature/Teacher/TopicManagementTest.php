<?php

namespace Tests\Feature\Teacher;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class TopicManagementTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_teacher_can_view_the_create_topic_form(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)
            ->get(route('teacher.topics.create', $subject))
            ->assertOk();
    }

    public function test_teacher_can_view_the_edit_topic_form(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $topic = Topic::factory()->for($subject)->create();

        $this->actingAs($teacher)
            ->get(route('teacher.topics.edit', [$subject, $topic]))
            ->assertOk()
            ->assertSee($topic->name);
    }

    public function test_teacher_can_create_a_topic_for_their_subject(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $response = $this->actingAs($teacher)->post(route('teacher.topics.store', $subject), [
            'name' => 'Yangi mavzu',
            'description' => 'Tavsif',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics']));
        $this->assertDatabaseHas('topics', ['subject_id' => $subject->id, 'name' => 'Yangi mavzu']);
    }

    public function test_creating_a_topic_requires_a_name(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)
            ->post(route('teacher.topics.store', $subject), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_teacher_can_update_a_topic(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $topic = Topic::factory()->for($subject)->create(['name' => 'Eski']);

        $this->actingAs($teacher)->put(route('teacher.topics.update', [$subject, $topic]), [
            'name' => 'Yangilangan mavzu',
            'position' => 5,
            'is_active' => '1',
        ])->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics']));

        $this->assertDatabaseHas('topics', ['id' => $topic->id, 'name' => 'Yangilangan mavzu', 'position' => 5]);
    }

    public function test_teacher_can_delete_a_topic(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $topic = Topic::factory()->for($subject)->create();

        $this->actingAs($teacher)
            ->delete(route('teacher.topics.destroy', [$subject, $topic]))
            ->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics']));

        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }

    public function test_topic_from_another_subject_cannot_be_edited_via_mismatched_route(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $otherSubject = Subject::factory()->for($teacher, 'teacher')->create();
        $topic = Topic::factory()->for($otherSubject, 'subject')->create();

        $this->actingAs($teacher)
            ->get(route('teacher.topics.edit', [$subject, $topic]))
            ->assertNotFound();
    }
}
