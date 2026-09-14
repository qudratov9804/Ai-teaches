<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTopicRequest;
use App\Http\Requests\Teacher\UpdateTopicRequest;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TopicController extends Controller
{
    /**
     * Show the form for creating a new topic under the given subject.
     */
    public function create(Subject $subject): View
    {
        $this->authorize('create', [Topic::class, $subject]);

        return view('teacher.topics.create', [
            'subject' => $subject,
        ]);
    }

    /**
     * Store a newly created topic.
     */
    public function store(StoreTopicRequest $request, Subject $subject): RedirectResponse
    {
        $subject->topics()->create($request->validated() + [
            'position' => $request->integer('position') ?: ($subject->topics()->max('position') + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics'])
            ->with('success', 'Mavzu muvaffaqiyatli yaratildi.');
    }

    /**
     * Show the form for editing the given topic.
     */
    public function edit(Subject $subject, Topic $topic): View
    {
        $this->authorizeTopicBelongsToSubject($subject, $topic);
        $this->authorize('update', $topic);

        return view('teacher.topics.edit', [
            'subject' => $subject,
            'topic' => $topic,
        ]);
    }

    /**
     * Update the given topic.
     */
    public function update(UpdateTopicRequest $request, Subject $subject, Topic $topic): RedirectResponse
    {
        $this->authorizeTopicBelongsToSubject($subject, $topic);

        $topic->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics'])
            ->with('success', 'Mavzu muvaffaqiyatli yangilandi.');
    }

    /**
     * Remove the given topic.
     */
    public function destroy(Subject $subject, Topic $topic): RedirectResponse
    {
        $this->authorizeTopicBelongsToSubject($subject, $topic);
        $this->authorize('delete', $topic);

        $topic->delete();

        return redirect()
            ->route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics'])
            ->with('success', "Mavzu o'chirildi.");
    }

    /**
     * Guard against mismatched parent/child route parameters (IDOR).
     */
    private function authorizeTopicBelongsToSubject(Subject $subject, Topic $topic): void
    {
        abort_unless($topic->subject_id === $subject->id, 404);
    }
}
