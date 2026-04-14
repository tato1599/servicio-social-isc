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
    public $selectedDepartmentId = null;
    public $selectedSubjectId = null;

    public function mount()
    {
        $firstDept = \App\Models\Department::first();
        if ($firstDept) {
            $this->selectedDepartmentId = $firstDept->id;
        }
    }

    public function selectDepartment($id)
    {
        $this->selectedDepartmentId = $id;
        $this->selectedSubjectId = null;
        $this->resetPage();
    }

    public function selectSubject($id)
    {
        $this->selectedSubjectId = $id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
        $this->selectedSubjectId = null;
        session()->flash('message', 'Materia eliminada correctamente.');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        $departments = \App\Models\Department::all();
        
        $query = Subject::with('department')
            ->where('department_id', $this->selectedDepartmentId);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        $subjects = $query->orderBy('semester')
            ->orderBy('name')
            ->get();

        $selectedSubject = $this->selectedSubjectId 
            ? Subject::with(['department'])->find($this->selectedSubjectId) 
            : ($subjects->first() ?? null);

        if ($selectedSubject && !$this->selectedSubjectId) {
            $this->selectedSubjectId = $selectedSubject->id;
        }

        return view('livewire.subjects.subject-index', [
            'subjects' => $subjects,
            'departments' => $departments,
            'selectedSubject' => $selectedSubject,
        ]);
    }
}
