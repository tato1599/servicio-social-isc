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
    public $previewData = [];

    public function mount()
    {
        $this->loadRecentCourses();
    }

    public function loadRecentCourses()
    {
        $this->recentCourses = Course::with(['subject', 'teacher', 'requirementClassroom'])
            ->where('period', $this->period)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Parse a single line of text into a structured data array.
     */
    protected function parseLine($line)
    {
        $line = trim($line);
        if (empty($line) || strlen($line) < 10) return null;

        // Heurística de detección por columnas
        if (preg_match('/^(\d)\s+([A-Z\s\.ÁÉÍÓÚÑ0-9\(\)´]+?)\s+([A-Z0-9]{3,7})\s+/u', $line, $matches)) {
            $semester = $matches[1];
            $name = trim($matches[2]);
            $code = $matches[3];
            $remaining = substr($line, strlen($matches[0]));

            // Extraer Métricas (Alumnos, Ajuste, Grupos)
            $metrics = [];
            if (preg_match_all('/\b(\d{1,3})\b/', $remaining, $numMatches)) {
                $metrics = $numMatches[1];
            }

            $students = $metrics[0] ?? 0;
            $adjusted = $metrics[1] ?? 0;
            $groupsCnt = $metrics[2] ?? 1;

            // Extraer Slot (Letra A-O sola)
            $slot = null;
            if (preg_match('/\b([A-O])\b/', $remaining, $slotMatch)) {
                $slot = $slotMatch[1];
            }

            // Extraer Salón (70X, 10X, 40X, etc)
            $roomName = null;
            if (preg_match('/\b(70[0-9]|1[0-9]{2}|40[0-9]|SALA|LAB|AULA|132|134|136|135|238|404|106)\b/i', $remaining, $roomMatch)) {
                $roomName = $roomMatch[0];
            }

            // Extraer Maestro
            $teacherName = null;
            if (preg_match('/\s+([A-ZÁÉÍÓÚÑ]{3,}\s+[A-ZÁÉÍÓÚÑ]{3,}.*)$/u', $line, $teacherMatch)) {
                $teacherName = trim($teacherMatch[1]);
                $teacherName = preg_replace('/(CANCELADO|REVISAR|VER|MATERIAS).*/i', '', $teacherName);
                $teacherName = trim($teacherName);
                if (strlen($teacherName) < 5) $teacherName = null;
            }

            return [
                'semester' => $semester,
                'subject_name' => $name,
                'subject_code' => $code,
                'students' => $students,
                'adjusted' => $adjusted,
                'groups' => $groupsCnt,
                'slot' => $slot,
                'room' => $roomName,
                'teacher' => $teacherName,
                'valid' => true
            ];
        }

        return null;
    }

    public function generatePreview()
    {
        $this->previewData = [];
        $lines = explode("\n", trim($this->rawData));
        
        foreach ($lines as $line) {
            $parsed = $this->parseLine($line);
            if ($parsed) {
                $this->previewData[] = $parsed;
            }
        }
        
        $this->message = "Se generó la vista previa de " . count($this->previewData) . " materias.";
    }

    public function import(\App\Services\ScheduleService $service)
    {
        if (empty($this->previewData)) {
            $this->generatePreview();
        }

        $importedCount = 0;
        $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);

        foreach ($this->previewData as $data) {
            // Crear Entidades
            $subject = Subject::updateOrCreate(
                ['code' => $data['subject_code']],
                ['name' => $data['subject_name'], 'semester' => $data['semester'], 'weekly_hours' => 5, 'department_id' => $deptISC->id]
            );

            $teacherId = null;
            if ($data['teacher']) {
                $teacher = Teacher::firstOrCreate(
                    ['name' => $data['teacher']],
                    ['employee_id' => 'EMP-' . rand(1000, 9999), 'department_id' => $deptISC->id]
                );
                $teacherId = $teacher->id;
            }

            $roomId = null;
            if ($data['room']) {
                $classroom = Classroom::firstOrCreate(['name' => $data['room']], ['building_id' => 1]);
                $roomId = $classroom->id;
            }

            // Crear Curso
            Course::updateOrCreate(
                [
                    'subject_id' => $subject->id, 
                    'period' => $this->period, 
                    'requirement_slot' => $data['slot'],
                    'teacher_id' => $teacherId
                ],
                [
                    'group_code' => 'A',
                    'study_plan' => 'ISC 2024',
                    'students_count' => $data['students'],
                    'students_count_adjusted' => $data['adjusted'],
                    'groups_count' => $data['groups'],
                    'requirement_classroom_id' => $roomId,
                ]
            );

            $importedCount++;
        }

        $service->generateFromRequirements($this->period);
        $this->loadRecentCourses();
        $this->previewData = [];

        $this->message = "¡Éxito! Se importaron definitivamente $importedCount registros.";
        $this->rawData = '';
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.raw-import');
    }
}
