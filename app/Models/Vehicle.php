<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function getCurrentMileageAttribute()
    {
        return $this->lessons()->max('end_mileage');
    }
}
