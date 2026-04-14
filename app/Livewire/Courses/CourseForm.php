<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Schedule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CourseForm extends Component
{
    public ?Course $course = null;

    public $subject_id;
    public $teacher_id;
    public $group_code = 'A';
    public $period = 'Aug-Dec 2026';

    public $students_count = 0;
    public $status = 'draft';
    public $possible_slots;
    public $final_slot;

    // Para los slots de horario
    public $schedules_data = [];

    protected $rules = [
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'nullable|exists:teachers,id',
        'group_code' => 'required|string|max:10',
        'period' => 'required|string|max:50',
        'students_count' => 'required|integer|min:0',
        'status' => 'required|in:draft,teacher_assigned,published',
        'possible_slots' => 'nullable|string',
        'final_slot' => 'nullable|string',
    ];

    public function mount(Course $course = null)
    {
        if ($course && $course->exists) {
            $this->course = $course;
            $this->subject_id = $course->subject_id;
            $this->teacher_id = $course->teacher_id;
            $this->group_code = $course->group_code;
            $this->period = $course->period;
            $this->students_count = $course->students_count;
            $this->status = $course->status;
            $this->possible_slots = $course->possible_slots;
            $this->final_slot = $course->final_slot;
            
            $this->schedules_data = $course->schedules->map(fn($s) => [
                'id' => $s->id,
                'classroom_id' => $s->classroom_id,
                'day_of_week' => $s->day_of_week,
                'start_time' => substr($s->start_time, 0, 5),
                'end_time' => substr($s->end_time, 0, 5),
            ])->toArray();
        }
    }

    public function addSchedule()
    {
        $this->schedules_data[] = [
            'classroom_id' => '',
            'day_of_week' => 1,
            'start_time' => '07:00',
            'end_time' => '08:00',
        ];
    }

    public function removeSchedule($index)
    {
        unset($this->schedules_data[$index]);
        $this->schedules_data = array_values($this->schedules_data);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id ?: null,
            'group_code' => $this->group_code,
            'period' => $this->period,
            'students_count' => $this->students_count,
            'status' => $this->status,
            'possible_slots' => $this->possible_slots,
            'final_slot' => $this->final_slot,
        ];

        if ($this->course && $this->course->exists) {
            $this->course->update($data);
        } else {
            $this->course = Course::create($data);
        }

        // Sincronizar horarios
        $this->course->schedules()->delete();
        foreach ($this->schedules_data as $slot) {
            if ($slot['classroom_id']) {
                $this->course->schedules()->create($slot);
            }
        }

        session()->flash('message', 'Curso y horarios guardados correctamente.');

        return redirect()->to(route('courses.index'));
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.courses.course-form', [
            'subjects' => Subject::all(),
            'teachers' => Teacher::all(),
            'classrooms' => Classroom::all(),
        ]);
    }
}
