<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $subjects = $request->user()
            ->subjectsTeaching()
            ->withCount(['topics', 'students', 'materials'])
            ->latest()
            ->get();

        return view('teacher.dashboard', [
            'subjects' => $subjects,
            'stats' => [
                'subjects' => $subjects->count(),
                'topics' => $subjects->sum('topics_count'),
                'materials' => $subjects->sum('materials_count'),
                'students' => $subjects->sum('students_count'),
            ],
        ]);
    }
}
