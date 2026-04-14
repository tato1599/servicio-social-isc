<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Classroom;
use App\Models\Schedule;
use Livewire\Attributes\Layout;

class ScheduleMatrix extends Component
{
    public $period = 'Aug-Dec 2024';
    public $dayOfWeek = 1; // Lunes por defecto
    public $matrix = [];
    public $classrooms = [];

    public $timeSlots = [
        'A' => '07:00 - 08:00',
        'B' => '08:00 - 09:00',
        'C' => '09:00 - 10:00',
        'D' => '10:00 - 11:00',
        'E' => '11:00 - 12:00',
        'F' => '12:00 - 13:00',
        'G' => '13:00 - 14:00',
        'H' => '14:00 - 15:00',
        'I' => '15:00 - 16:00',
        'J' => '16:00 - 17:00',
        'K' => '17:00 - 18:00',
        'L' => '18:00 - 19:00',
        'M' => '19:00 - 20:00',
        'N' => '20:00 - 21:00',
        'O' => '21:00 - 22:00',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->classrooms = Classroom::orderBy('name')->get();

        $schedules = Schedule::with(['course.subject', 'course.teacher'])
            ->where('day_of_week', $this->dayOfWeek)
            ->whereHas('course', fn($q) => $q->where('period', $this->period))
            ->get();

        // Inicializar matriz vacía
        $this->matrix = [];
        foreach ($this->slots as $letter => $time) {
            foreach ($this->classrooms as $classroom) {
                $this->matrix[$letter][$classroom->id] = null;
            }
        }

        // Llenar matriz
        foreach ($schedules as $s) {
            $startTime = substr($s->start_time, 0, 5);
            $letter = array_search($startTime . ' - ' . date('H:i', strtotime($startTime) + 3600), $this->slots);

            if ($letter) {
                $this->matrix[$letter][$s->classroom_id] = [
                    'id' => $s->id,
                    'subject_code' => $s->course->subject->code,
                    'subject_name' => $s->course->subject->name,
                    'teacher_name' => $s->course->teacher->name ?? 'Pendiente',
                    'semester' => $s->course->subject->semester,
                    'color' => $this->getColorForSemester($s->course->subject->semester)
                ];
            }
        }
    }

    public function setDay($day)
    {
        $this->dayOfWeek = $day;
        $this->loadData();
    }

    private function getColorForSemester($semester)
    {
        $colors = [
            1 => 'bg-blue-100 border-blue-200 text-blue-700',
            2 => 'bg-emerald-100 border-emerald-200 text-emerald-700',
            3 => 'bg-amber-100 border-amber-200 text-amber-700',
            4 => 'bg-purple-100 border-purple-200 text-purple-700',
            5 => 'bg-rose-100 border-rose-200 text-rose-700',
            6 => 'bg-indigo-100 border-indigo-200 text-indigo-700',
            7 => 'bg-cyan-100 border-cyan-200 text-cyan-700',
            8 => 'bg-orange-100 border-orange-200 text-orange-700',
            9 => 'bg-pink-100 border-pink-200 text-pink-700',
        ];

        return $colors[$semester] ?? 'bg-gray-100 border-gray-200 text-gray-700';
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.schedule-matrix');
    }
}
