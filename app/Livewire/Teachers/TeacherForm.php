<?php

namespace App\Livewire\Teachers;

use App\Models\Department;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TeacherForm extends Component
{
    public ?Teacher $teacher = null;

    public $name;
    public $employee_id;
    public $type = 'base';
    public $priority = 0;
    public $max_weekly_hours;
    public $department_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'employee_id' => 'nullable|string|max:50',
        'type' => 'required|in:base,honorarios',
        'priority' => 'required|integer|min:0',
        'max_weekly_hours' => 'nullable|integer|min:1',
        'department_id' => 'required|exists:departments,id',
    ];

    public function mount(Teacher $teacher = null)
    {
        if ($teacher && $teacher->exists) {
            $this->teacher = $teacher;
            $this->name = $teacher->name;
            $this->employee_id = $teacher->employee_id;
            $this->type = $teacher->type;
            $this->priority = $teacher->priority;
            $this->max_weekly_hours = $teacher->max_weekly_hours;
            $this->department_id = $teacher->department_id;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'employee_id' => $this->employee_id,
            'type' => $this->type,
            'priority' => $this->priority,
            'max_weekly_hours' => $this->max_weekly_hours,
            'department_id' => $this->department_id,
        ];

        if ($this->teacher && $this->teacher->exists) {
            $this->teacher->update($data);
            $message = 'Maestro actualizado correctamente.';
        } else {
            Teacher::create($data);
            $message = 'Maestro registrado correctamente.';
        }

        session()->flash('message', $message);

        return redirect()->to(route('teachers.index'));
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.teachers.teacher-form', [
            'departments' => Department::all(),
        ]);
    }
}
