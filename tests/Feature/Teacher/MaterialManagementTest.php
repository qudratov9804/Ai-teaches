<?php

namespace Tests\Feature\Teacher;

use App\Models\Material;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class MaterialManagementTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_teacher_can_view_the_upload_material_form(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)
            ->get(route('teacher.materials.create', $subject))
            ->assertOk();
    }

    public function test_teacher_can_upload_a_material(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $response = $this->actingAs($teacher)->post(route('teacher.materials.store', $subject), [
            'title' => 'Database Fundamentals',
            'type' => Material::TYPE_TEXTBOOK,
            'file' => UploadedFile::fake()->create('book.pdf', 300, 'application/pdf'),
        ]);

        $response->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials']));
        $this->assertDatabaseHas('materials', [
            'subject_id' => $subject->id,
            'title' => 'Database Fundamentals',
            'uploaded_by' => $teacher->id,
        ]);
    }

    public function test_material_can_optionally_be_linked_to_a_topic(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $topic = Topic::factory()->for($subject)->create();

        $this->actingAs($teacher)->post(route('teacher.materials.store', $subject), [
            'title' => 'SQL SELECT',
            'type' => Material::TYPE_ARTICLE,
            'topic_id' => $topic->id,
            'file' => UploadedFile::fake()->create('sql.pdf', 100, 'application/pdf'),
        ]);

        $this->assertDatabaseHas('materials', ['title' => 'SQL SELECT', 'topic_id' => $topic->id]);
    }

    public function test_material_rejects_a_topic_belonging_to_another_subject(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $otherSubject = Subject::factory()->for($teacher, 'teacher')->create();
        $foreignTopic = Topic::factory()->for($otherSubject, 'subject')->create();

        $this->actingAs($teacher)->post(route('teacher.materials.store', $subject), [
            'title' => 'Noto\'g\'ri mavzu',
            'type' => Material::TYPE_OTHER,
            'topic_id' => $foreignTopic->id,
            'file' => UploadedFile::fake()->create('x.pdf', 50, 'application/pdf'),
        ])->assertSessionHasErrors('topic_id');
    }

    public function test_uploading_a_new_material_does_not_remove_previous_materials(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        Material::factory()->for($subject)->create(['uploaded_by' => $teacher->id]);

        $this->actingAs($teacher)->post(route('teacher.materials.store', $subject), [
            'title' => 'Yangi material',
            'type' => Material::TYPE_OTHER,
            'file' => UploadedFile::fake()->create('y.pdf', 50, 'application/pdf'),
        ]);

        $this->assertSame(2, $subject->materials()->count());
    }

    public function test_material_upload_rejects_disallowed_extensions(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)->post(route('teacher.materials.store', $subject), [
            'title' => 'Zararli fayl',
            'type' => Material::TYPE_OTHER,
            'file' => UploadedFile::fake()->create('virus.exe', 10, 'application/x-msdownload'),
        ])->assertSessionHasErrors('file');
    }

    public function test_teacher_can_view_a_material_detail_page(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $material = Material::factory()->for($subject)->create(['uploaded_by' => $teacher->id]);

        $this->actingAs($teacher)
            ->get(route('teacher.materials.show', $material))
            ->assertOk()
            ->assertSee($material->title);
    }

    public function test_teacher_can_delete_a_material(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $material = Material::factory()->for($subject)->create(['uploaded_by' => $teacher->id]);

        $this->actingAs($teacher)
            ->delete(route('teacher.materials.destroy', $material))
            ->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials']));

        $this->assertSoftDeleted('materials', ['id' => $material->id]);
    }

    public function test_materials_list_can_be_searched_by_title(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        Material::factory()->for($subject)->create(['title' => 'Findable Book', 'uploaded_by' => $teacher->id]);
        Material::factory()->for($subject)->create(['title' => 'Other Document', 'uploaded_by' => $teacher->id]);

        $response = $this->actingAs($teacher)
            ->get(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials', 'search' => 'Findable']));

        $response->assertOk()->assertSee('Findable Book')->assertDontSee('Other Document');
    }
}
