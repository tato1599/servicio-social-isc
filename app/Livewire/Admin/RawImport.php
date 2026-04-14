<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Course;
use App\Services\ScheduleService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

class RawImport extends Component
{
    use WithFileUploads;

    public $file; // Archivo CSV
    public $message = '';
    public $period = '';
    public $recentCourses = [];
    public $previewData = [];

    public function mount()
    {
        $this->loadRecentCourses();
        if (now()->month >= 1 && now()->month <= 7) {
            $this->period = 'Ene-Jun ' . now()->year;
        } else {
            $this->period = 'Ago-Dic ' . now()->year;
        }
    }

    #[Computed()]
    public function loadRecentCourses()
    {
        $this->recentCourses = Course::with(['subject', 'teacher', 'requirementClassroom'])
            ->where('period', $this->period)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    // Se ejecuta automáticamente al seleccionar un archivo
    public function updatedFile()
    {
        $this->generatePreview();
    }

    public function generatePreview()
    {
        $this->validate([
            'file' => 'required|mimes:csv,txt|max:2048', // Validación del archivo
        ]);

        $this->previewData = [];
        $filePath = $this->file->getRealPath();

        if (($handle = fopen($filePath, 'r')) !== false) {
            // Leer la primera fila (encabezados) y omitirla
            $headers = fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Validar que la fila no esté vacía y tenga al menos la columna de Materia y Clave
                if (empty($row[1]) || empty($row[2])) {
                    continue;
                }

                // Extraer slots de horarios (Columnas 6 a 10)
                $slots = [];
                for ($i = 6; $i <= 10; $i++) {
                    if (isset($row[$i]) && trim($row[$i]) !== '') {
                        // Por si hay espacios dentro de la misma celda como "D     B"
                        $cellSlots = preg_split('/\s+/', trim($row[$i]));
                        foreach ($cellSlots as $s) {
                            if (!empty($s)) {
                                $slots[] = $s;
                            }
                        }
                    }
                }

                $this->previewData[] = [
                    'semester'     => trim($row[0] ?? ''),
                    'subject_name' => trim($row[1] ?? ''),
                    'subject_code' => trim($row[2] ?? ''),
                    'students'     => is_numeric($row[3]) ? (int)$row[3] : 0,
                    'adjusted'     => is_numeric($row[4]) ? (float)$row[4] : 0,
                    'groups'       => is_numeric($row[5]) ? (int)$row[5] : 0,
                    'slot'         => !empty($slots) ? implode(', ', array_unique($slots)) : 'N/A',
                    'room'         => null, // No viene en el CSV actual
                    'teacher'      => null, // No viene en el CSV actual
                    'valid'        => true
                ];
            }
            fclose($handle);
        }

        $this->message = "Se generó la vista previa de " . count($this->previewData) . " materias desde el CSV.";
    }

    public function import(ScheduleService $service)
    {
        if (empty($this->previewData)) {
            $this->addError('file', 'No hay datos validados para importar.');
            return;
        }

        $importedCount = 0;
        $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);

        foreach ($this->previewData as $data) {
            // Crear Entidad Materia
            $subject = Subject::updateOrCreate(
                ['code' => $data['subject_code']],
                ['name' => $data['subject_name'], 'semester' => $data['semester'], 'weekly_hours' => 5, 'department_id' => $deptISC->id]
            );

            // Mantenemos la lógica de profesores/salón por si en el futuro los incluyes en el CSV
            $teacherId = null;
            if (!empty($data['teacher'])) {
                $teacher = Teacher::firstOrCreate(
                    ['name' => $data['teacher']],
                    ['employee_id' => 'EMP-' . rand(1000, 9999), 'department_id' => $deptISC->id]
                );
                $teacherId = $teacher->id;
            }

            $roomId = null;
            if (!empty($data['room'])) {
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
        $this->reset('file'); // Limpiamos el input file
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.raw-import');
    }
}
