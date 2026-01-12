<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    /** @use HasFactory<\Database\Factories\ChargeFactory> */
    use HasFactory;

    public function casts()
    {
        return [
            'amount' => 'float',
            'entered' => 'datetime',
        ];
    }
}
