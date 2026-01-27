<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    protected $fillable = ['student_id', 'amount', 'date','type','check_number','auth_number','employee_id','last_update','updated_by',
        'remarks','last_four'];

    public function casts()
    {
        return [
            'date' => 'datetime',
            'amount' => 'float',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
