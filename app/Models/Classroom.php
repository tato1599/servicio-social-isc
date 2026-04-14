<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'building_id', 'capacity'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
