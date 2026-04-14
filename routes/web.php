<?php

use App\Livewire\Teachers\TeacherForm;
use App\Livewire\Teachers\TeacherIndex;
use App\Livewire\Teachers\TeacherShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/generate', [App\Http\Controllers\DashboardController::class, 'generate'])->name('dashboard.generate');
    Route::post('/dashboard/generate-requirements', [App\Http\Controllers\DashboardController::class, 'generateFromRequirements'])->name('dashboard.generate-requirements');
    Route::get('/import', \App\Livewire\Admin\ScheduleWizard::class)->name('admin.raw-import');
    Route::get('/carga-academica', \App\Livewire\Admin\AcademicLoad::class)->name('admin.academic-load');
    Route::get('/matriz', \App\Livewire\Admin\ScheduleMatrix::class)->name('admin.schedule-matrix');

    // --- Módulo de Docentes ---
    Route::prefix('teachers')->group(function () {
        Route::get('/', TeacherIndex::class)->name('teachers.index');
        Route::get('/create', TeacherForm::class)->name('teachers.create');
        Route::get('/{teacher}/edit', TeacherForm::class)->name('teachers.edit');
        Route::get('/{teacher}', TeacherShow::class)->name('teachers.show');
    });

    // --- Módulo de Materias ---
    Route::prefix('subjects')->group(function () {
        Route::get('/', \App\Livewire\Subjects\SubjectIndex::class)->name('subjects.index');
        Route::get('/create', \App\Livewire\Subjects\SubjectForm::class)->name('subjects.create');
        Route::get('/{subject}/edit', \App\Livewire\Subjects\SubjectForm::class)->name('subjects.edit');
    });

    // --- Módulo de Horarios (Asignaciones) ---
    Route::prefix('courses')->group(function () {
        Route::get('/', \App\Livewire\Courses\ScheduleCalendar::class)->name('courses.index');
        Route::get('/create', \App\Livewire\Courses\CourseForm::class)->name('courses.create');
        Route::get('/{course}/edit', \App\Livewire\Courses\CourseForm::class)->name('courses.edit');
    });
});
