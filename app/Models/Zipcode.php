<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    /** @use HasFactory<\Database\Factories\ZipcodeFactory> */
    use HasFactory;

    protected $primaryKey = 'zipcode';
}
