<?php

namespace App\Livewire\Classrooms;

use App\Models\Classroom;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ClassroomIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteClassroom(Classroom $classroom)
    {
        $classroom->delete();
        session()->flash('message', 'Salón de clases eliminado correctamente.');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $classrooms = Classroom::with('building')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('building', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.classrooms.classroom-index', [
            'classrooms' => $classrooms,
        ]);
    }
}
