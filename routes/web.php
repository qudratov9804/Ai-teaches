<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Teacher\CurriculumController as TeacherCurriculumController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\MaterialController as TeacherMaterialController;
use App\Http\Controllers\Teacher\SubjectController as TeacherSubjectController;
use App\Http\Controllers\Teacher\TopicController as TeacherTopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', LogoutController::class)->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::get('subjects/{subject}', [TeacherSubjectController::class, 'show'])->name('subjects.show');
        Route::put('subjects/{subject}', [TeacherSubjectController::class, 'update'])->name('subjects.update');

        Route::get('subjects/{subject}/topics/create', [TeacherTopicController::class, 'create'])->name('topics.create');
        Route::post('subjects/{subject}/topics', [TeacherTopicController::class, 'store'])->name('topics.store');
        Route::get('subjects/{subject}/topics/{topic}/edit', [TeacherTopicController::class, 'edit'])->name('topics.edit');
        Route::put('subjects/{subject}/topics/{topic}', [TeacherTopicController::class, 'update'])->name('topics.update');
        Route::delete('subjects/{subject}/topics/{topic}', [TeacherTopicController::class, 'destroy'])->name('topics.destroy');

        Route::post('subjects/{subject}/curriculum', [TeacherCurriculumController::class, 'store'])->name('curriculum.store');
        Route::get('curriculum/{curriculum}/download', [TeacherCurriculumController::class, 'download'])->name('curriculum.download');
        Route::delete('curriculum/{curriculum}', [TeacherCurriculumController::class, 'destroy'])->name('curriculum.destroy');

        Route::get('subjects/{subject}/materials/create', [TeacherMaterialController::class, 'create'])->name('materials.create');
        Route::post('subjects/{subject}/materials', [TeacherMaterialController::class, 'store'])->name('materials.store');
        Route::get('materials/{material}', [TeacherMaterialController::class, 'show'])->name('materials.show');
        Route::get('materials/{material}/download', [TeacherMaterialController::class, 'download'])->name('materials.download');
        Route::delete('materials/{material}', [TeacherMaterialController::class, 'destroy'])->name('materials.destroy');
    });

    Route::get('student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard')
        ->middleware('role:student');
});
