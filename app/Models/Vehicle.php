<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function casts()
    {
        return [
            'active' => 'boolean',
            'date_purchased' => 'date',
            'date_sold' => 'date',
            'mileage' => 'decimal:1',
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function getCurrentMileageAttribute()
    {
        return $this->lessons()->max('end_mileage');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
