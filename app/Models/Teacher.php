<?php

namespace App\Models;

use App\Traits\BelongsToDepartment;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use BelongsToDepartment;

    protected $fillable = [
        'name',
        'employee_id',
        'type',
        'priority',
        'min_hours',
        'max_hours',
        'department_id',
    ];


    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
