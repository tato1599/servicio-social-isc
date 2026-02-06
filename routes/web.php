<?php

use App\Livewire\Teachers\TeacherForm;
use App\Livewire\Teachers\TeacherIndex;
use App\Livewire\Teachers\TeacherShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- Módulo de Docentes ---
    Route::prefix('teachers')->group(function () {
        Route::get('/', TeacherIndex::class)->name('teachers.index');
        Route::get('/create', TeacherForm::class)->name('teachers.create');
        Route::get('/{teacher}/edit', TeacherForm::class)->name('teachers.edit');
        Route::get('/{teacher}', TeacherShow::class)->name('teachers.show');
    });

});
