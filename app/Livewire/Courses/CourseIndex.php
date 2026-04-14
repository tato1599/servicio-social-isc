<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CourseIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteCourse(Course $course)
    {
        $course->delete();
        session()->flash('message', 'Curso eliminado correctamente.');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $courses = Course::with(['teacher', 'subject', 'schedules'])
            ->whereHas('teacher', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('subject', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.courses.course-index', [
            'courses' => $courses,
        ]);
    }
}
