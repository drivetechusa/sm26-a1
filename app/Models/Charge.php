<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    /** @use HasFactory<\Database\Factories\ChargeFactory> */
    use HasFactory;
    protected $fillable = ['student_id', 'amount', 'entered', 'reason','updated_by','employee_id'];
    public function casts()
    {
        return [
            'amount' => 'float',
            'entered' => 'datetime',
        ];
    }
}
