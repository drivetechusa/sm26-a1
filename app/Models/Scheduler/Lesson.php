<?php

namespace App\Models\Scheduler;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $connection = 'scheduler';
    protected $table = 'lesson';
    const CREATED_AT = 'createdat';
    const UPDATED_AT = 'updatedat';

    protected $fillable = ['start_time','end_time','sessionnotes','zone_id','student_id','instructor_id','pulocation_id','complete',
        'hide','created_by','updated_by'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'complete' => 'boolean',
        'hide' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getTotalTimeAttribute()
    {
        $time = round($this->start_time->floatDiffInHours($this->end_time), 2);
        return $time;
    }
}
