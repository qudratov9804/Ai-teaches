<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreCurriculumRequest;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CurriculumController extends Controller
{
    public function __construct(private readonly FileUploadService $uploads) {}

    /**
     * Store a newly uploaded curriculum document for the given subject.
     *
     * Existing curriculum documents are preserved; a new upload is simply
     * added as the next version so historical versions stay available.
     */
    public function store(StoreCurriculumRequest $request, Subject $subject): RedirectResponse
    {
        $meta = $this->uploads->store($request->file('file'), "curricula/{$subject->id}");

        $nextVersion = (int) $subject->curriculums()->max('version') + 1;

        $subject->curriculums()->create([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'version' => $nextVersion,
            'is_active' => true,
            'uploaded_by' => $request->user()->id,
            ...$meta,
        ]);

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'curriculum'])
            ->with('success', "O'quv dasturi muvaffaqiyatli yuklandi.");
    }

    /**
     * Download the given curriculum document.
     */
    public function download(Curriculum $curriculum): StreamedResponse|RedirectResponse
    {
        $this->authorize('view', $curriculum);

        if (! Storage::disk(config('uploads.disk'))->exists($curriculum->file_path)) {
            return back()->with('error', 'Fayl topilmadi. U serverdan olib tashlangan bo\'lishi mumkin.');
        }

        return Storage::disk(config('uploads.disk'))
            ->download($curriculum->file_path, $curriculum->original_name);
    }

    /**
     * Remove the given curriculum document (record is soft-deleted for audit history).
     */
    public function destroy(Curriculum $curriculum): RedirectResponse
    {
        $this->authorize('delete', $curriculum);

        $subject = $curriculum->subject;
        $curriculum->delete();

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'curriculum'])
            ->with('success', "O'quv dasturi hujjati o'chirildi.");
    }
}
