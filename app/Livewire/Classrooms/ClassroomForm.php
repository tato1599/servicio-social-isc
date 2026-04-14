<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ClassroomForm extends Component
{
    public ?Classroom $classroom = null;

    public $name;
    public $building_id;
    public $capacity = 30;

    protected $rules = [
        'name' => 'required|string|max:255',
        'building_id' => 'required|exists:buildings,id',
        'capacity' => 'required|integer|min:1',
    ];

    public function mount(Classroom $classroom = null)
    {
        if ($classroom && $classroom->exists) {
            $this->classroom = $classroom;
            $this->name = $classroom->name;
            $this->building_id = $classroom->building_id;
            $this->capacity = $classroom->capacity;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'building_id' => $this->building_id,
            'capacity' => $this->capacity,
        ];

        if ($this->classroom && $this->classroom->exists) {
            $this->classroom->update($data);
            $message = 'Salón actualizado correctamente.';
        } else {
            Classroom::create($data);
            $message = 'Salón registrado correctamente.';
        }

        session()->flash('message', $message);

        return redirect()->to(route('classrooms.index'));
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.classrooms.classroom-form', [
            'buildings' => Building::all(),
        ]);
    }
}
