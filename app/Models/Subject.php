<?php

namespace App\Models;

use App\Traits\BelongsToDepartment;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use BelongsToDepartment;

    protected $fillable = [
        'code',
        'name',
        'semester',
        'weekly_hours',
        'department_id',
    ];

}
