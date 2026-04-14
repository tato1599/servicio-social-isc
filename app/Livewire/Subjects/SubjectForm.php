<?php

namespace App\Livewire\Subjects;

use App\Models\Department;
use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SubjectForm extends Component
{
    public ?Subject $subject = null;

    public $code;
    public $name;
    public $semester;
    public $weekly_hours = 4;
    public $department_id;

    protected $rules = [
        'code' => 'required|string|max:50|unique:subjects,code',
        'name' => 'required|string|max:255',
        'semester' => 'required|integer|min:1|max:12',
        'weekly_hours' => 'required|integer|min:1',
        'department_id' => 'required|exists:departments,id',
    ];

    public function mount(Subject $subject = null)
    {
        if ($subject && $subject->exists) {
            $this->subject = $subject;
            $this->code = $subject->code;
            $this->name = $subject->name;
            $this->semester = $subject->semester;
            $this->weekly_hours = $subject->weekly_hours;
            $this->department_id = $subject->department_id;
            
            // Allow unique validation to bypass current record
            $this->rules['code'] = 'required|string|max:50|unique:subjects,code,' . $subject->id;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'semester' => $this->semester,
            'weekly_hours' => $this->weekly_hours,
            'department_id' => $this->department_id,
        ];

        if ($this->subject && $this->subject->exists) {
            $this->subject->update($data);
            $message = 'Materia actualizada correctamente.';
        } else {
            Subject::create($data);
            $message = 'Materia registrada correctamente.';
        }

        session()->flash('message', $message);

        return redirect()->to(route('subjects.index'));
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.subjects.subject-form', [
            'departments' => Department::all(),
        ]);
    }
}
