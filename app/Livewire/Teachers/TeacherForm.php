<?php

namespace App\Livewire\Teachers;

use Livewire\Attributes\Layout;
use Livewire\Component;

class TeacherForm extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.teachers.teacher-form');
    }
}
