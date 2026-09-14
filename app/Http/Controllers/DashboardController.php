<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect the user to their role-specific dashboard.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match ($user->role?->slug) {
            Role::ADMIN => redirect()->route('admin.dashboard'),
            Role::TEACHER => redirect()->route('teacher.dashboard'),
            Role::STUDENT => redirect()->route('student.dashboard'),
            default => abort(403, 'Foydalanuvchiga rol biriktirilmagan.'),
        };
    }
}
