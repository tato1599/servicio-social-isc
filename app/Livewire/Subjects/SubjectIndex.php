<?php

namespace App\Livewire\Subjects;

use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
        session()->flash('message', 'Materia eliminada correctamente.');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $subjects = Subject::with('department')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy('semester')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.subjects.subject-index', [
            'subjects' => $subjects,
        ]);
    }
}
