<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'type',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}