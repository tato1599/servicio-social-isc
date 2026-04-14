<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'code',
        'name',
        'semester',
        'weekly_hours',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
