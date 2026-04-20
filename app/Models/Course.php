<?php

namespace App\Models;

use App\Traits\BelongsToDepartment;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use BelongsToDepartment;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'group_code',
        'period',
        'possible_slots',
        'final_slot',
        'status',
        'requirement_slot',
        'requirement_classroom_id',
        'study_plan',
        'students_count',
        'students_count_adjusted',
        'groups_count',
        'department_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function requirementClassroom()
    {
        return $this->belongsTo(Classroom::class, 'requirement_classroom_id');
    }
}
