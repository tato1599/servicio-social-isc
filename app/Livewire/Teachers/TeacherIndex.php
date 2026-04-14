<?php

namespace App\Livewire\Teachers;

use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteTeacher(Teacher $teacher)
    {
        $teacher->delete();
        session()->flash('message', 'Maestro eliminado correctamente.');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $teachers = Teacher::with('department')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('employee_id', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.teachers.teacher-index', [
            'teachers' => $teachers,
        ]);
    }
}
