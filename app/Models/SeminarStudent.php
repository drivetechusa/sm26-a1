<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeminarStudent extends Pivot
{
    protected $table = 'seminar_student';
}
