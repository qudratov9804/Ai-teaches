<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'teacherCount' => User::whereRelation('role', 'slug', '=', 'teacher')->count(),
            'studentCount' => User::whereRelation('role', 'slug', '=', 'student')->count(),
            'subjectCount' => Subject::count(),
            'groupCount' => Group::count(),
        ]);
    }
}
