<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonFactory> */
    use HasFactory;

    protected $fillable = ['student_id', 'type', 'employee_id', 'sessionnotes','start_time','end_time','zone_id','pulocation_id',
        'complete','hide','created_by','updated_by','vehicle_id','begin_mileage','end_mileage','lesson_number'];

    public function casts()
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function instructor()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
