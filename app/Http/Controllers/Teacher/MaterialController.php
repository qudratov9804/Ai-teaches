<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreMaterialRequest;
use App\Models\Material;
use App\Models\Subject;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function __construct(private readonly FileUploadService $uploads) {}

    /**
     * Show the form for uploading a new material under the given subject.
     */
    public function create(Subject $subject): View
    {
        $this->authorize('create', [Material::class, $subject]);

        return view('teacher.materials.create', [
            'subject' => $subject->load('topics'),
            'materialTypes' => Material::types(),
        ]);
    }

    /**
     * Store a newly uploaded material.
     *
     * Materials accumulate over time; uploading a new one never replaces
     * or hides previously uploaded materials for the subject.
     */
    public function store(StoreMaterialRequest $request, Subject $subject): RedirectResponse
    {
        $meta = $this->uploads->store($request->file('file'), "materials/{$subject->id}");

        $subject->materials()->create($request->safe()->except('file') + [
            'is_active' => true,
            'uploaded_by' => $request->user()->id,
            ...$meta,
        ]);

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials'])
            ->with('success', 'Material muvaffaqiyatli yuklandi.');
    }

    /**
     * Show a single material's metadata.
     */
    public function show(Material $material): View
    {
        $this->authorize('view', $material);

        return view('teacher.materials.show', [
            'material' => $material->load(['subject', 'topic', 'uploader']),
        ]);
    }

    /**
     * Download the given material's file.
     */
    public function download(Material $material): StreamedResponse|RedirectResponse
    {
        $this->authorize('view', $material);

        if (! Storage::disk(config('uploads.disk'))->exists($material->file_path)) {
            return back()->with('error', 'Fayl topilmadi. U serverdan olib tashlangan bo\'lishi mumkin.');
        }

        return Storage::disk(config('uploads.disk'))
            ->download($material->file_path, $material->original_name);
    }

    /**
     * Remove the given material (soft-deleted for audit history).
     */
    public function destroy(Material $material): RedirectResponse
    {
        $this->authorize('delete', $material);

        $subject = $material->subject;
        $material->delete();

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials'])
            ->with('success', "Material o'chirildi.");
    }
}
