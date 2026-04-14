<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ScheduleCalendar extends Component
{
    public $events = [];
    public $conflicts = [];
    public $period = 'Aug-Dec 2026';

    public function mount(\App\Services\ScheduleService $service)
    {
        $this->loadData($service);
    }

    public function loadData(\App\Services\ScheduleService $service)
    {
        $schedules = \App\Models\Schedule::with(['course.subject', 'course.teacher', 'classroom'])
            ->whereHas('course', fn($q) => $q->where('period', $this->period))
            ->get();

        $this->events = $schedules->map(function ($s) {
            // FullCalendar usa 0-6 para Sun-Sat, pero nosotros 1-5 para Lun-Vie
            // Mapeo: 1 (Lun) -> 1, 2 (Mar) -> 2...
            // Usaremos la lógica de ISO (1-7)
            
            // Colores por materia
            $colors = ['#137fec', '#F5A623', '#D0021B', '#417505', '#9013FE'];
            $color = $colors[$s->course->subject_id % count($colors)];

            return [
                'id' => $s->id,
                'title' => $s->course->subject->code . ' - ' . $s->course->teacher->name,
                'daysOfWeek' => [$s->day_of_week],
                'startTime' => $s->start_time,
                'endTime' => $s->end_time,
                'extendedProps' => [
                    'classroom' => $s->classroom->name,
                    'subject' => $s->course->subject->name,
                ],
                'backgroundColor' => $color . '22', // 20% opacity
                'borderColor' => $color,
                'textColor' => $color,
            ];
        })->toArray();

        $this->conflicts = $service->detectConflicts($this->period);
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.courses.schedule-calendar');
    }
}
