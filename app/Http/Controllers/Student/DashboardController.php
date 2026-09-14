<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $subjects = $request->user()
            ->subjects()
            ->withCount('topics')
            ->with('teacher')
            ->get();

        return view('student.dashboard', [
            'subjects' => $subjects,
        ]);
    }
}
