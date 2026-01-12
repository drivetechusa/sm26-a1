<?php

namespace App\Models\Scheduler;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $connection = 'scheduler';

    protected $fillable = ['dtdb_id','name','email','password'];
}
