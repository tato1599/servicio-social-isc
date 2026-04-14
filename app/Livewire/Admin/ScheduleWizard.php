<?php

namespace App\Livewire\Admin;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Helpers\TimeSlotHelper;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class ScheduleWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Paso 1: Importación CSV
    public $file;
    public $previewData = [];
    public $importedCourses = [];
    public $period = '';

    // Paso 2: Maestros
    public $teachers = [];
    public $selectedTeachers = []; // Mapeo de course_id => teacher_id
    public $coursesStep2 = [];

    // Paso 3: Salones y Horarios
    public $classrooms = [];
    public $coursesStep3 = [];
    // arrays dimensionados con course_id como key
    public $selectedFinalSlots = [];
    public $selectedClassrooms = []; 

    public function mount()
    {
        if (now()->month >= 1 && now()->month <= 7) {
            $this->period = 'Ene-Jun ' . now()->year;
        } else {
            $this->period = 'Ago-Dic ' . now()->year;
        }
    }

    public function updatedFile()
    {
        $this->processCsv();
    }

    public function processCsv()
    {
        $this->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $this->previewData = [];
        $filePath = $this->file->getRealPath();

        if (($handle = fopen($filePath, 'r')) !== false) {
            // Ignorar encabezados
            $headers = fgetcsv($handle, 1000, ',');

            $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (empty($row[1]) || empty($row[2])) {
                    continue;
                }

                $slots = [];
                for ($i = 6; $i <= 10; $i++) {
                    if (isset($row[$i]) && trim($row[$i]) !== '') {
                        $cellSlots = preg_split('/\s+/', trim($row[$i]));
                        foreach ($cellSlots as $s) {
                            if (!empty($s) && preg_match('/^[A-O]$/i', $s)) {
                                $slots[] = strtoupper($s);
                            }
                        }
                    }
                }
                
                $slots = array_values(array_unique($slots));

                $semester = (int)trim($row[0] ?? 0);
                $subjectName = trim($row[1]);
                $subjectCode = trim($row[2]);
                $studentsCount = is_numeric($row[3]) ? (int)$row[3] : 0;

                // Crear o actualizar materia
                $subject = Subject::updateOrCreate(
                    ['code' => $subjectCode],
                    [
                        'name' => $subjectName,
                        'semester' => $semester > 0 ? $semester : 1,
                        'weekly_hours' => 5,
                        'department_id' => $deptISC->id
                    ]
                );

                // Crear o actualizar curso en estado 'draft'
                $course = Course::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'period' => $this->period,
                        'group_code' => 'A' // asumiendo 'A' para todos los creados aqui
                    ],
                    [
                        'students_count' => $studentsCount,
                        'possible_slots' => empty($slots) ? null : json_encode($slots),
                        'status' => 'draft'
                    ]
                );

                $this->previewData[] = [
                    'course_id' => $course->id,
                    'subject_name' => $subject->name,
                    'subject_code' => $subject->code,
                    'students' => $studentsCount,
                    'slots' => implode(', ', $slots)
                ];
            }
            fclose($handle);
        }

        $this->importedCourses = $this->previewData;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->loadStep2();
        } elseif ($this->currentStep === 2) {
            $this->saveStep2();
            $this->loadStep3();
        }
        
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function loadStep2()
    {
        $this->teachers = Teacher::orderBy('name')->get();
        // Cargar cursos en draft o teacher_assigned
        $this->coursesStep2 = Course::with('subject')
            ->where('period', $this->period)
            ->whereIn('status', ['draft', 'teacher_assigned'])
            ->get();

        foreach ($this->coursesStep2 as $course) {
            if ($course->teacher_id) {
                $this->selectedTeachers[$course->id] = $course->teacher_id;
            }
        }
    }

    public function getTeacherWorkloadsProperty()
    {
        $workloads = [];
        // Calcular cargas actuales con lo guardado en BD y sumarle el cambio en vivo (selectedTeachers)
        $courses = Course::with('subject')->where('period', $this->period)->get();
        
        foreach ($this->teachers as $teacher) {
            $workloads[$teacher->id] = [
                'name' => $teacher->name,
                'min' => $teacher->min_hours,
                'max' => $teacher->max_hours,
                'assigned' => 0
            ];
        }

        foreach ($courses as $c) {
            // El maestro final es el que esté modificado en UI, o el original si no está en UI, o si no se toca
            $tid = isset($this->selectedTeachers[$c->id]) ? $this->selectedTeachers[$c->id] : $c->teacher_id;
            
            if ($tid && isset($workloads[$tid]) && $c->subject) {
                $workloads[$tid]['assigned'] += $c->subject->weekly_hours;
            }
        }

        return $workloads;
    }

    public function saveStep2()
    {
        foreach ($this->selectedTeachers as $courseId => $teacherId) {
            if ($teacherId) {
                Course::where('id', $courseId)->update([
                    'teacher_id' => $teacherId,
                    'status' => 'teacher_assigned'
                ]);
            }
        }
    }

    public function loadStep3()
    {
        $this->classrooms = Classroom::orderBy('name')->get();
        $this->coursesStep3 = Course::with(['subject', 'teacher'])
            ->where('period', $this->period)
            ->whereIn('status', ['teacher_assigned', 'published'])
            ->get();

        foreach ($this->coursesStep3 as $course) {
            $this->selectedFinalSlots[$course->id] = $course->final_slot;
            $this->selectedClassrooms[$course->id] = $course->requirement_classroom_id; // Se usa provisionalemente
        }
    }

    public function publishSchedules()
    {
        foreach ($this->coursesStep3 as $course) {
            $finalSlot = $this->selectedFinalSlots[$course->id] ?? null;
            $classroomId = $this->selectedClassrooms[$course->id] ?? null;

            if ($finalSlot && $classroomId) {
                // Actualizar info final en curso
                $course->update([
                    'final_slot' => $finalSlot,
                    'requirement_classroom_id' => $classroomId,
                    'status' => 'published'
                ]);

                // Generar los registros de Schedules!
                // Limpiar previos
                Schedule::where('course_id', $course->id)->delete();

                $times = TimeSlotHelper::getSlotTimes($finalSlot);
                if ($times) {
                    // Según las weekly_hours asignamos de lunes a X
                    $days = min(5, $course->subject->weekly_hours); // maximo de Lunes a Sabado es 6, pero usaré maximo viernes 5
                    for ($day = 1; $day <= $days; $day++) {
                        Schedule::create([
                            'course_id' => $course->id,
                            'classroom_id' => $classroomId,
                            'day_of_week' => $day,
                            'start_time' => $times['start'],
                            'end_time' => $times['end'],
                        ]);
                    }
                }
            }
        }

        session()->flash('success', '¡Horarios publicados exitosamente!');
        // Se puede reiniciar o ir al principio
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.schedule-wizard');
    }
}
