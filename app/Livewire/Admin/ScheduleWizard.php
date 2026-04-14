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

    // Paso 2: Configuración de final_slots
    public $coursesStep2 = [];
    public $selectedFinalSlots = [];

    // Paso 3: Aulas (Arrastrar a cabeceras)
    public $classrooms = [];

    // Paso 4: Maestros (Arrastrar a celdas)
    public $teachers = [];

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
            $headers = fgetcsv($handle, 1000, ',');
            $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (empty($row[1]) || empty($row[2])) {
                    continue;
                }

                $groupsCount = is_numeric($row[5]) ? (int)$row[5] : 0;
                if ($groupsCount <= 0) {
                    continue;
                }

                $semester = (int)trim($row[0] ?? 0);
                $subjectName = trim($row[1]);
                $subjectCode = trim($row[2]);
                $studentsCount = is_numeric($row[3]) ? (int)$row[3] : 0;

                $subject = Subject::updateOrCreate(
                    ['code' => $subjectCode],
                    [
                        'name' => $subjectName,
                        'semester' => $semester > 0 ? $semester : 1,
                        'weekly_hours' => 5,
                        'department_id' => $deptISC->id
                    ]
                );

                for ($g = 0; $g < $groupsCount; $g++) {
                    $groupCode = chr(65 + $g);
                    $slotColumnIndex = 6 + $g;
                    $rawSlot = $row[$slotColumnIndex] ?? '';

                    $slots = [];
                    $cellSlots = preg_split('/\s+/', trim($rawSlot));
                    foreach ($cellSlots as $s) {
                        if (!empty($s) && preg_match('/^[A-O]$/i', $s)) {
                            $slots[] = strtoupper($s);
                        }
                    }
                    $slots = array_values(array_unique($slots));

                    $course = Course::updateOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'period' => $this->period,
                            'group_code' => $groupCode
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
                        'group' => $groupCode,
                        'students' => $studentsCount,
                        'slots' => empty($slots) ? 'POR DEFINIR' : implode(', ', $slots)
                    ];
                }
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
        } elseif ($this->currentStep === 3) {
            if ($this->getUnassignedClassroomCourses()->count() > 0) {
                $this->dispatch('toast-error', message: 'Por favor, asigna salón a todos los cursos antes de avanzar.');
                return;
            }
            $this->loadStep4();
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
        if ($this->currentStep === 2) {
            $this->loadStep2();
        } elseif ($this->currentStep === 3) {
            $this->loadStep3();
        }
    }

    // --- PASO 2: CONFIRMAR HORARIOS ---
    public function loadStep2()
    {
        $this->coursesStep2 = Course::with('subject')
            ->where('period', $this->period)
            ->get();
            
        foreach ($this->coursesStep2 as $course) {
            // Si el curso ya tiene final_slot de BD, cargarlo. Sino, pre-seleccionar el primer possible slot
            $defaultSlot = null;
            if ($course->possible_slots) {
                $possible = json_decode($course->possible_slots, true);
                $defaultSlot = !empty($possible) ? $possible[0] : null;
            }
            $this->selectedFinalSlots[$course->id] = $course->final_slot ?? $defaultSlot;
        }
    }

    public function saveStep2()
    {
        foreach ($this->selectedFinalSlots as $courseId => $slot) {
            if ($slot) {
                Course::where('id', $courseId)->update([
                    'final_slot' => $slot,
                    'status' => 'teacher_assigned' // Subimos el estado para simular avance
                ]);
            }
        }
    }

    // --- PASO 3: SALONES ---
    public function loadStep3()
    {
        $this->classrooms = Classroom::orderBy('name')->get();
    }

    public function getUnassignedClassroomCourses()
    {
        return Course::with(['subject'])
            ->where('period', $this->period)
            ->whereNotNull('final_slot')
            ->whereNull('requirement_classroom_id')
            ->orderBy('final_slot')
            ->get();
    }

    public function getMatrixStep3()
    {
        // Se construye la matriz para ver a los que sí han sido asignados
        $assignedCourses = Course::with(['subject'])
            ->where('period', $this->period)
            ->whereNotNull('final_slot')
            ->whereNotNull('requirement_classroom_id')
            ->get();

        $matrix = [];
        foreach (\App\Helpers\TimeSlotHelper::SLOTS as $slotKey => $slotData) {
            $matrix[$slotKey] = [];
        }

        foreach ($assignedCourses as $course) {
            $cId = $course->requirement_classroom_id;
            $slot = $course->final_slot;

            $matrix[$slot][$cId] = [
                'id' => $course->id,
                'subject_name' => $course->subject->name ?? '',
                'group' => $course->group_code,
                'students_count' => $course->students_count
            ];
        }

        return $matrix;
    }

    public function assignCourseToClassroom($courseId, $classroomId)
    {
        $course = Course::find($courseId);
        if ($course && $course->final_slot) {
            // Validate overlapping conflict
            $conflict = Course::where('period', $this->period)
                ->where('final_slot', $course->final_slot)
                ->where('requirement_classroom_id', $classroomId)
                ->where('id', '!=', $courseId)
                ->exists();

            if ($conflict) {
                $this->dispatch('toast-error', message: 'El bloque ' . $course->final_slot . ' ya está ocupado en esta aula.');
                return;
            }

            $course->update([
                'requirement_classroom_id' => $classroomId
            ]);
            
            // $this->dispatch('toast-success', message: 'Aula asignada correctamente'); // Obviamos toast verde por cada drop normal para no saturar al usuario, a menos que fallé.
        }
    }

    public function removeCourseFromClassroom($courseId)
    {
        $course = Course::find($courseId);
        if ($course) {
            $course->update([
                'requirement_classroom_id' => null,
                'teacher_id' => null // reseteamos maestro x si acaso
            ]);
        }
    }

    // --- PASO 4: MAESTROS ---
    public function loadStep4()
    {
        $this->classrooms = Classroom::orderBy('name')->get();
    }

    public function getTeachersWithLoad()
    {
        $teachers = Teacher::orderBy('name')->get();
        $assignedCourses = Course::with('subject')
            ->where('period', $this->period)
            ->whereNotNull('teacher_id')
            ->get();

        $loads = $assignedCourses->groupBy('teacher_id')->map(function($teacherCourses) {
            return $teacherCourses->sum(function($c) {
                return $c->subject->weekly_hours ?? 5;
            });
        });

        foreach($teachers as $teacher) {
            $teacher->current_hours = $loads->get($teacher->id, 0);
            
            if ($teacher->current_hours > $teacher->max_hours) {
                $teacher->status_color = 'bg-red-50 text-red-700 border-red-200';
            } elseif ($teacher->current_hours < $teacher->min_hours) {
                $teacher->status_color = 'bg-yellow-50 text-yellow-700 border-yellow-200';
            } else {
                $teacher->status_color = 'bg-green-50 text-green-700 border-green-200';
            }
        }

        return $teachers;
    }

    public function getMatrixStep4()
    {
        $assignedCourses = Course::with(['subject', 'teacher'])
            ->where('period', $this->period)
            ->whereNotNull('final_slot')
            ->whereNotNull('requirement_classroom_id')
            ->get();

        $matrix = [];
        foreach (\App\Helpers\TimeSlotHelper::SLOTS as $slotKey => $slotData) {
            $matrix[$slotKey] = [];
        }

        foreach ($assignedCourses as $course) {
            $cId = $course->requirement_classroom_id;
            $slot = $course->final_slot;

            $matrix[$slot][$cId] = [
                'id' => $course->id,
                'subject_name' => $course->subject->name ?? '',
                'group' => $course->group_code,
                'teacher' => $course->teacher->name ?? null, // puede ser null
                'students_count' => $course->students_count
            ];
        }

        return $matrix;
    }

    public function assignTeacherToCourse($teacherId, $courseId)
    {
        $course = Course::find($courseId);
        if ($course && $course->final_slot) {
            // Validate: Teacher already scheduled at this slot
            $conflict = Course::where('period', $this->period)
                ->where('teacher_id', $teacherId)
                ->where('final_slot', $course->final_slot)
                ->where('id', '!=', $courseId)
                ->exists();

            if ($conflict) {
                $this->dispatch('toast-error', message: 'El maestro ya imparte una clase en el bloque ' . $course->final_slot . '.');
                return;
            }

            $course->update([
                'teacher_id' => $teacherId
            ]);
        }
    }

    public function removeTeacherFromCourse($courseId)
    {
        $course = Course::find($courseId);
        if ($course) {
            $course->update([
                'teacher_id' => null
            ]);
        }
    }

    // --- FIN Y PUBLICACIÓN ---
    public function publishSchedules()
    {
        $unassignedTeachers = Course::where('period', $this->period)
            ->whereNotNull('final_slot')
            ->whereNotNull('requirement_classroom_id')
            ->whereNull('teacher_id')
            ->exists();

        if ($unassignedTeachers) {
            $this->dispatch('toast-error', message: 'Falta asignar maestro a uno o más cursos confirmados.');
            return;
        }

        $publishedCourses = Course::with(['subject'])
            ->where('period', $this->period)
            ->whereNotNull('final_slot')
            ->whereNotNull('requirement_classroom_id')
            ->get();

        foreach ($publishedCourses as $course) {
            $finalSlot = $course->final_slot;
            $classroomId = $course->requirement_classroom_id;

            $course->update([
                'status' => 'published'
            ]);

            Schedule::where('course_id', $course->id)->delete();

            $times = TimeSlotHelper::getSlotTimes($finalSlot);
            if ($times) {
                $days = min(5, $course->subject->weekly_hours ?? 5);
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

        $this->dispatch('toast-success', message: '¡Horarios publicados exitosamente!');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.admin.schedule-wizard');
    }
}
