<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    /** @use HasFactory<\Database\Factories\SeminarFactory> */
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['classroom_id', 'employee_id', 'date', 'full','class_type','cancelled','add_info','sale_price'];

    public function casts()
    {
        return [
            'date' => 'datetime',
            'full' => 'boolean',
            'cancelled' => 'boolean',
        ];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'seminar_student')->using(SeminarStudent::class)->withPivot('level','discount','tuition')->orderBy('lastname','asc');
    }

    public function getEmailsAttribute(): array
    {
        $emails = array();
        foreach ($this->students as $student)
        {
            if (!in_array($student->email, $emails))
            {
                $emails[] = $student->email;
            }
            if (!in_array($student->guardian_2_email, $emails))
            {
                $emails[] = $student->guardian_2_email;
            }
            if (!in_array($student->email_student, $emails))
            {
                $emails[] = $student->email_student;
            }
        }
        //$filtered = $emails->filter()->unique();

        return array_filter($emails);
    }


}
