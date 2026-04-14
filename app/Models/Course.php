<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'group_code',
        'period',
        'requirement_slot',
        'requirement_classroom_id',
        'study_plan',
        'students_count',
        'students_count_adjusted',
        'groups_count',
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
}
