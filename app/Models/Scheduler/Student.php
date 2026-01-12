<?php

namespace App\Models\Scheduler;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'scheduler';
    protected $table = 'student';
    const CREATED_AT = 'createdat';
    const UPDATED_AT = 'updatedat';

    protected $fillable = ['school_id','firstname','middlename','lastname','suffix','dob','address','address1','city','state','zip',
        'phone_parent','phone_home','phone_student','email_parent','email_student','start_date','end_date','lpn','lpn_doi','class_id',
        'ssn','username','drivetime','drivetimecompleted','status','lxl','zone_id','home_pickup','created_by','updated_by','high_school',
        'password','instructor_id'];

    public function getDisplayNameAttribute()
    {
        return "{$this->lastname}, {$this->firstname}";
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
