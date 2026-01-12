<?php

namespace App\Models;

use App\Enums\StudentStatus;
use App\Enums\StudentTypes;
use App\Livewire\Forms\SeminarForm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'stu_web_id', 'school_id','firstname','middlename','lastname','suffix','street','street1','zip_id', 'phone','secondary_phone',
        'email', 'dob','status','type','date_started','date_completed','permit_number', 'issue_date','renewal_date', 'zone_id','home_pickup',
        'ssn', 'username', 'drive_time_purchased','drive_time_completed','permit_verified','contract','high_school','email_student','parent_name',
        'parent_name_alternate', 'student_phone','gender','goes_by','pickup_location_id','guardian_2_email','neighborhood','instructor_id','parent_relationship',
        'parent_alternate_relationship'
    ];

    public function casts()
    {
        return [
            'status' => StudentStatus::class,
            'type' => StudentTypes::class,
            'dob' => 'date',
            'date_started' => 'date',
            'date_completed' => 'date',
            'issue_date' => 'date',
            'renewal_date' => 'date'
        ];
    }

    public function getFullNameAttribute()
    {
        $name = $this->lastname;
        $this->suffix ? $name = $name . ' ' . $this->suffix : $name;
        $name = $name . ', ' . $this->firstname;
        $this->middlename ? $name = $name . ' ' . $this->middlename : $name;
        return strtoupper($name);
    }

    public function getAddressAttribute()
    {
        $address = $this->street;
        $this->street1 ? $address = $address . ', ' . $this->street1 : $address;
        return $address;
    }

    public function getCszAttribute()
    {
        $zipcode = $this->zipcode;
        if ($zipcode instanceof Zipcode) {
            return "{$zipcode->city}, {$zipcode->state} {$zipcode->zipcode}";
        } else {
            return 'Zipcode not found';
        }
    }

    public function getDobAgeAttribute()
    {
        if ($this->dob instanceof \Carbon\Carbon) {
            return "{$this->dob->format('m/d/Y')} ({$this->dob->age})";
        } else {
            return 'Date of birth not found';
        }
    }

    public function getStartDateAttribute()
    {
        if ($this->date_started instanceof \Carbon\Carbon) {
            return "{$this->date_started->format('m/d/Y')}";
        } else {
            return '';
        }
    }

    public function getCompletedDateAttribute()
    {
        if ($this->date_completed instanceof \Carbon\Carbon) {
            return "{$this->date_completed->format('m/d/Y')}";
        } else {
            return '';
        }
    }

    public function getDateIssuedAttribute()
    {
        if ($this->issue_date instanceof \Carbon\Carbon) {
            return "{$this->issue_date->format('m/d/Y')}";
        } else {
            return '';
        }
    }

    public function getEligibleDateAttribute()
    {
        if ($this->issue_date instanceof \Carbon\Carbon) {
            $eligible = clone($this->issue_date)->addDays(181);
            return "{$eligible->format('m/d/Y')}";
        } else {
            return '';
        }
    }

    public function charges()
    {
        return $this->hasMany(Charge::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function seminars()
    {
        return $this->belongsToMany(Seminar::class, 'seminar_student')->using(SeminarStudent::class)->withPivot('level','discount','tuition')->orderBy('date', 'desc');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    public function Zipcode()
    {
        return $this->belongsTo(Zipcode::class, 'zip_id', 'zipcode');
    }

    public function instructor()
    {
        return $this->belongsTo(Employee::class, 'instructor_id', 'id');
    }

    public function enroller()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id');
    }

    public function getDisplayBirthdateAttribute()
    {
        return optional($this->dob)->format('m/d/y') ?: '';
    }

    public function getNotificationEmailsAttribute($value)
    {
        $emails = array();
        if ($this->email_student) {
            array_push($emails, $this->email_student);
        }
        if ($this->email) {
            array_push($emails, $this->email);
        }
        if ($this->guardian_2_email) {
            array_push($emails, $this->guardian_2_email);
        }

        return $emails;
    }
    public function getDisplayNameAttribute()
    {
        return "{$this->lastname}, {$this->firstname}";
    }

    public function getAgeAttribute()
    {
        return $this->dob->age;
    }
    public function getDisplayCompletionDateAttribute()
    {
        return optional($this->date_completed)->format('m/d/y') ?: '';
    }
    public function getContractNameAttribute()
    {
        if (!empty($this->middlename))
        {
            return "{$this->firstname} {$this->lastname} {$this->suffix}";
        } else {
            return "{$this->firstname} {$this->middlename} {$this->lastname} {$this->suffix}";
        }

    }

    public function getContractAddressAttribute()
    {
        return "{$this->street}, {$this->street1}, {$this->zipcode->city}, {$this->zipcode->state} {$this->zipcode->zipcode}";
    }

    public function getBalanceAttribute()
    {
        $payments = $this->payments()->sum('amount');
        $charges = $this->charges()->sum('amount');
        return $charges - $payments;
    }

    public function scheduledLessons()
    {
        return $this->hasMany(\App\Models\Scheduler\Lesson::class, 'student_id', 'stu_web_id')->where('complete', false)->orderBy('start_time', 'asc');
    }

    public function ascLessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('start_time', 'asc');
    }
}
