<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Show the publicly browsable list of open subjects.
     *
     * Logged-in students additionally see the subjects assigned to them,
     * even if those subjects are not marked open.
     */
    public function index(Request $request): View
    {
        $mySubjects = collect();

        if ($request->user()?->isStudent()) {
            $mySubjects = $request->user()
                ->subjects()
                ->with('teacher')
                ->withCount('topics')
                ->get();
        }

        return view('subjects.index', [
            'mySubjects' => $mySubjects,
        ]);
    }

    public function show(Request $request, Subject $subject): View
    {
        $this->authorize('view', $subject);

        $canManage = $request->user()
            && ($request->user()->isAdmin() || $request->user()->id === $subject->teacher_id);

        $subject->load('teacher');
        $subject->load(['topics' => function ($query) use ($canManage) {
            if (! $canManage) {
                $query->where('is_active', true);
            }
        }]);

        return view('subjects.show', [
            'subject' => $subject,
        ]);
    }
}
