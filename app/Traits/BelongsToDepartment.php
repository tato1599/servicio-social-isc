<?php

namespace App\Traits;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToDepartment
{
    protected static function booted()
    {
        static::addGlobalScope('department', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                
                // If user is not admin and has a department_id, filter by it
                if (!$user->isAdmin() && $user->department_id) {
                    $builder->where($builder->getQuery()->from . '.department_id', $user->department_id);
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && !$model->department_id) {
                $model->department_id = auth()->user()->department_id;
            }
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
