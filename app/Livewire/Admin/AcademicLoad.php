<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Livewire\Attributes\Layout;

class AcademicLoad extends Component
{
    public $period = 'Aug-Dec 2024';
    public $courses = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->courses = Course::with(['subject', 'teacher'])
            ->where('period', $this->period)
            ->get()
            ->map(function ($course) {
                // Parsear los slots (B,C,D...) para llenar H1-H5
                $slots = explode(',', $course->requirement_slot);
                $course->h1 = $slots[0] ?? '-';
                $course->h2 = $slots[1] ?? '-';
                $course->h3 = $slots[2] ?? '-';
                $course->h4 = $slots[3] ?? '-';
                $course->h5 = $slots[4] ?? '-';
                return $course;
            });
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.academic-load');
    }
}
