<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateSubjectRequest;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Show the subject management page (overview, topics, curriculum, materials tabs).
     */
    public function show(Request $request, Subject $subject): View
    {
        $this->authorize('update', $subject);

        $subject->load('topics');

        $materials = $subject->materials()
            ->with('topic')
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->string('type')->toString(), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->integer('topic_id'), function ($query, $topicId) {
                $query->where('topic_id', $topicId);
            })
            ->orderBy(
                match ($request->string('sort')->toString()) {
                    'oldest' => 'created_at',
                    'name' => 'title',
                    default => 'created_at',
                },
                $request->string('sort')->toString() === 'name' ? 'asc' : 'desc'
            )
            ->paginate(10)
            ->withQueryString();

        return view('teacher.subjects.show', [
            'subject' => $subject,
            'curriculums' => $subject->curriculums()->with('uploader')->get(),
            'materials' => $materials,
            'materialTypes' => Material::types(),
            'tab' => $request->string('tab')->toString() ?: 'overview',
        ]);
    }

    /**
     * Update the subject's own information.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated() + [
            'is_open' => $request->boolean('is_open'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('teacher.subjects.show', $subject)
            ->with('success', 'Fan muvaffaqiyatli yangilandi.');
    }
}
