<?php

namespace App\Livewire\Teachers;

use Livewire\Attributes\Layout;
use Livewire\Component;

class TeacherShow extends Component
{
    public \App\Models\Teacher $teacher;

    public function mount(\App\Models\Teacher $teacher)
    {
        $this->teacher = $teacher->load(['courses.subject', 'department']);
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $stats = [
            'total_hours' => $this->teacher->courses->sum(fn($c) => $c->subject->weekly_hours),
            'total_subjects' => $this->teacher->courses->count(),
            'total_students' => rand(150, 250), // Mocked for now
        ];

        return view('livewire.teachers.teacher-show', compact('stats'));
    }
}
