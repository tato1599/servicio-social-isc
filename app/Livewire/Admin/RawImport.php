<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Course;
use App\Services\ScheduleService;
use Livewire\Attributes\Layout;

class RawImport extends Component
{
    public $rawData = '';
    public $message = '';
    public $period = 'Aug-Dec 2024';
    public $recentCourses = [];

    public function mount()
    {
        $this->loadRecentCourses();
    }

    public function loadRecentCourses()
    {
        $this->recentCourses = Course::with(['subject', 'teacher'])
            ->where('period', $this->period)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }

    public function import(\App\Services\ScheduleService $service)
    {
        $lines = explode("\n", trim($this->rawData));
        $importedCount = 0;
        
        $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Regex para capturar: Semestre | Nombre | Clave | Alumnos | Ajuste | Grupos
            if (preg_match('/^([1-9])\s+([A-Z\s\.0-9ÁÉÍÓÚÑ]+?)\s+([A-Z0-9]{3,7})\s+(\d+)\s+(\d+)\s+(\d+)(.*)/u', $line, $matches)) {
                $semester = $matches[1];
                $name = trim($matches[2]);
                $code = $matches[3];
                $students = $matches[4];
                $adjusted = $matches[5];
                $groups = $matches[6];
                $remaining = $matches[7];

                preg_match_all('/\b([A-O])\b/', $remaining, $slotMatches);
                $foundSlots = implode(',', $slotMatches[1]);

                $subject = Subject::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name, 
                        'semester' => $semester, 
                        'weekly_hours' => count($slotMatches[1]) ?: 5, 
                        'department_id' => $deptISC->id
                    ]
                );

                Course::updateOrCreate(
                    ['subject_id' => $subject->id, 'period' => $this->period, 'group_code' => 'A'],
                    [
                        'study_plan' => 'ISC 2024',
                        'students_count' => $students,
                        'students_count_adjusted' => $adjusted,
                        'groups_count' => $groups,
                        'requirement_slot' => $foundSlots,
                        // teacher_id ya es nullable
                    ]
                );

                $importedCount++;
            }
        }

        $service->generateFromRequirements($this->period);
        $this->loadRecentCourses();

        $this->message = "Se procesaron $importedCount materias satisfactoriamente.";
        $this->rawData = '';
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.raw-import');
    }
}
