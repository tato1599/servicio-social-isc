<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * Engine for automatic schedule assignment.
     */
    public function generateAutomaticSchedules($period = 'Aug-Dec 2026')
    {
        // 1. Obtener cursos sin horario completo
        $courses = Course::with(['teacher', 'subject'])
            ->where('period', $period)
            ->get()
            ->sortByDesc(fn($c) => $c->teacher->priority);

        $classrooms = Classroom::all();
        $days = [1, 2, 3, 4, 5]; // Lun-Vie
        $startHour = 7;
        $endHour = 20;

        $assignedCount = 0;

        foreach ($courses as $course) {
            $hoursNeeded = $course->subject->weekly_hours;
            $hoursAssigned = 0;

            // Intentar asignar en bloques de 1 hora
            for ($day = 1; $day <= 5 && $hoursAssigned < $hoursNeeded; $day++) {
                for ($hour = $startHour; $hour < $endHour && $hoursAssigned < $hoursNeeded; $hour++) {
                    
                    $startTime = sprintf("%02d:00:00", $hour);
                    $endTime = sprintf("%02d:00:00", $hour + 1);

                    // Validar si el maestro ya tiene clase
                    if ($this->teacherIsBusy($course->teacher_id, $day, $startTime, $endTime)) {
                        continue;
                    }

                    // Encontrar salón disponible
                    $classroom = $this->findAvailableClassroom($classrooms, $day, $startTime, $endTime);

                    if ($classroom) {
                        Schedule::create([
                            'course_id' => $course->id,
                            'classroom_id' => $classroom->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                        ]);
                        $hoursAssigned++;
                        $assignedCount++;
                    }
                }
            }
        }

        return $assignedCount;
    }

    private function teacherIsBusy($teacherId, $day, $start, $end)
    {
        return Schedule::where('day_of_week', $day)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end]);
            })
            ->whereHas('course', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->exists();
    }

    private function findAvailableClassroom($classrooms, $day, $start, $end)
    {
        foreach ($classrooms as $room) {
            $isOccupied = Schedule::where('classroom_id', $room->id)
                ->where('day_of_week', $day)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end]);
                })
                ->exists();

            if (!$isOccupied) {
                return $room;
            }
        }

        return null;
    }

    public function generateFromRequirements($period = 'Aug-Dec 2024')
    {
        $courses = Course::where('period', $period)
            ->whereNotNull('requirement_slot')
            ->get();

        $slotMap = [
            'A' => '07:00:00', 'B' => '08:00:00', 'C' => '09:00:00', 
            'D' => '10:00:00', 'E' => '11:00:00', 'F' => '12:00:00',
            'G' => '13:00:00', 'H' => '14:00:00', 'I' => '15:00:00',
            'J' => '16:00:00', 'K' => '17:00:00', 'L' => '18:00:00',
            'M' => '19:00:00', 'N' => '20:00:00', 'O' => '21:00:00'
        ];

        $assignedCount = 0;

        foreach ($courses as $course) {
            $slots = explode(',', $course->requirement_slot);
            $hoursAssigned = 0;
            $hoursNeeded = $course->subject->weekly_hours;

            // Intentar asignar los slots recomendados en los 5 días de la semana
            // (Esta es una simplificación: si dice 'B', intentamos poner 1 hora B cada día hasta completar)
            foreach ($slots as $slot) {
                if (!isset($slotMap[$slot])) continue;

                $startTime = $slotMap[$slot];
                $endTime = date('H:i:s', strtotime($startTime) + 3600);

                for ($day = 1; $day <= 5 && $hoursAssigned < $hoursNeeded; $day++) {
                    // Verificar si ya hay algo en ese salón/hora/día
                    $isOccupied = Schedule::where('day_of_week', $day)
                        ->where('start_time', $startTime)
                        ->where(function($q) use ($course) {
                            $q->where('classroom_id', $course->requirement_classroom_id)
                              ->orWhereHas('course', fn($sq) => $sq->where('teacher_id', $course->teacher_id));
                        })
                        ->exists();

                    if (!$isOccupied) {
                        Schedule::create([
                            'course_id' => $course->id,
                            'classroom_id' => $course->requirement_classroom_id ?? Classroom::first()->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                        ]);
                        $hoursAssigned++;
                        $assignedCount++;
                    }
                }
            }
        }

        return $assignedCount;
    }

    public function detectConflicts($period = 'Aug-Dec 2024')
    {
        $conflicts = [];
        $schedules = Schedule::with(['course.teacher', 'course.subject', 'classroom'])
            ->whereHas('course', fn($q) => $q->where('period', $period))
            ->get();

        foreach ($schedules as $s1) {
            foreach ($schedules as $s2) {
                if ($s1->id === $s2->id) continue;

                // Mismo día
                if ($s1->day_of_week === $s2->day_of_week) {
                    // Solape de tiempo
                    $overlap = ($s1->start_time < $s2->end_time && $s1->end_time > $s2->start_time);

                    if ($overlap) {
                        // Conflicto de Maestro
                        if ($s1->course->teacher_id === $s2->course->teacher_id) {
                            $conflicts[] = [
                                'type' => 'teacher',
                                'title' => 'Conflicto de Maestro',
                                'name' => $s1->course->teacher->name,
                                'description' => "Asignado a dos clases al mismo tiempo.",
                                'items' => [
                                    "{$s1->course->subject->code}: {$s1->start_time} - {$s1->end_time}",
                                    "{$s2->course->subject->code}: {$s2->start_time} - {$s2->end_time}",
                                ],
                                'schedule_ids' => [$s1->id, $s2->id]
                            ];
                        }
                        // Conflicto de Salón
                        if ($s1->classroom_id === $s2->classroom_id) {
                            $conflicts[] = [
                                'type' => 'classroom',
                                'title' => 'Conflicto de Salón',
                                'name' => $s1->classroom->name,
                                'description' => "Reservado para dos clases al mismo tiempo.",
                                'items' => [
                                    "{$s1->course->subject->code} ({$s1->course->teacher->name})",
                                    "{$s2->course->subject->code} ({$s2->course->teacher->name})",
                                ],
                                'schedule_ids' => [$s1->id, $s2->id]
                            ];
                        }
                    }
                }
            }
        }

        // Eliminar duplicados simples (esto es una demo, la lógica real sería más compleja)
        return collect($conflicts)->unique(function ($item) {
            sort($item['schedule_ids']);
            return $item['type'] . implode('-', $item['schedule_ids']);
        })->values()->all();
    }
}
