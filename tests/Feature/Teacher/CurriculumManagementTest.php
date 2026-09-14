<?php

namespace Tests\Feature\Teacher;

use App\Models\Curriculum;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class CurriculumManagementTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_teacher_can_view_the_curriculum_tab(): void
    {
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        Curriculum::factory()->for($subject)->create(['uploaded_by' => $teacher->id]);

        $this->actingAs($teacher)
            ->get(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'curriculum']))
            ->assertOk();
    }

    public function test_teacher_can_upload_a_curriculum_document(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $response = $this->actingAs($teacher)->post(route('teacher.curriculum.store', $subject), [
            'title' => "Ishchi o'quv dasturi",
            'file' => UploadedFile::fake()->create('dastur.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'curriculum']));
        $this->assertDatabaseHas('curriculums', [
            'subject_id' => $subject->id,
            'title' => "Ishchi o'quv dasturi",
            'version' => 1,
        ]);

        $curriculum = Curriculum::first();
        Storage::disk('local')->assertExists($curriculum->file_path);
    }

    public function test_uploading_a_second_curriculum_document_keeps_the_first_and_increments_version(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)->post(route('teacher.curriculum.store', $subject), [
            'title' => 'Versiya 1',
            'file' => UploadedFile::fake()->create('v1.pdf', 200, 'application/pdf'),
        ]);

        $this->actingAs($teacher)->post(route('teacher.curriculum.store', $subject), [
            'title' => 'Versiya 2',
            'file' => UploadedFile::fake()->create('v2.pdf', 200, 'application/pdf'),
        ]);

        $this->assertSame(2, $subject->curriculums()->count());
        $this->assertDatabaseHas('curriculums', ['title' => 'Versiya 1', 'version' => 1]);
        $this->assertDatabaseHas('curriculums', ['title' => 'Versiya 2', 'version' => 2]);
    }

    public function test_curriculum_upload_rejects_disallowed_extensions(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)->post(route('teacher.curriculum.store', $subject), [
            'title' => "Ishchi o'quv dasturi",
            'file' => UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
        ])->assertSessionHasErrors('file');
    }

    public function test_curriculum_upload_rejects_oversized_files(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();

        $this->actingAs($teacher)->post(route('teacher.curriculum.store', $subject), [
            'title' => "Ishchi o'quv dasturi",
            'file' => UploadedFile::fake()->create('katta.pdf', 60000, 'application/pdf'),
        ])->assertSessionHasErrors('file');
    }

    public function test_teacher_can_download_their_own_curriculum_document(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        Storage::disk('local')->put('curricula/1/sample.pdf', 'content');
        $curriculum = Curriculum::factory()->for($subject)->create([
            'file_path' => 'curricula/1/sample.pdf',
            'uploaded_by' => $teacher->id,
        ]);

        $this->actingAs($teacher)
            ->get(route('teacher.curriculum.download', $curriculum))
            ->assertOk();
    }

    public function test_teacher_can_delete_a_curriculum_document(): void
    {
        Storage::fake('local');
        $teacher = $this->teacher();
        $subject = Subject::factory()->for($teacher, 'teacher')->create();
        $curriculum = Curriculum::factory()->for($subject)->create(['uploaded_by' => $teacher->id]);

        $this->actingAs($teacher)
            ->delete(route('teacher.curriculum.destroy', $curriculum))
            ->assertRedirect(route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'curriculum']));

        $this->assertSoftDeleted('curriculums', ['id' => $curriculum->id]);
    }
}
