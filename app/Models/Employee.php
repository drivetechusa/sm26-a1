<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Employee extends Authenticatable
{
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'namesuffix',
        'street',
        'street1',
        'zip_id',
        'phone',
        'secondary_phone',
        'email',
        'dob',
        'ssn',
        'hire_date',
        'term_date',
        'dl_license',
        'dl_expire',
        'inst_license',
        'school_id',
        'user_level',
        'active',
        'cdtp_instructor_number',
        'username',
        'password',
        'scheduler_id',
        'sched_instructor_id',
        'vehicle_id',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'dob' => 'date',
            'hire_date' => 'date',
            'term_date' => 'date',
            'dl_expire' => 'date',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function getNameAttribute(): string
    {
        return "{$this->lastname}, {$this->firstname}";
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
