<?php

namespace App\Livewire\Teachers;

use Livewire\Attributes\Layout;
use Livewire\Component;

class TeacherIndex extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.teachers.teacher-index');
    }
}
