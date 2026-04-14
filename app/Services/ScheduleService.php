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
}
