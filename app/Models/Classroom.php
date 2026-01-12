<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;

    public function casts()
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }

    public function zipcode()
    {
        return $this->hasOne(Zipcode::class, 'zipcode', 'zip_id');
    }

    public function getAddressAttribute()
    {
        return "{$this->street}, {$this->zipcode->city}, {$this->zipcode->state} {$this->zipcode->zipcode}";
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public static function activeClassroomOptions()
    {
        $classrooms = Classroom::active()->get();
        $options = [];
        foreach ($classrooms as $classroom) {
            $options[$classroom->id] = $classroom->name;
        }

        return $options;
    }
}
