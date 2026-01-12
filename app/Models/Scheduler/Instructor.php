<?php

namespace App\Models\Scheduler;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    public const CREATED_AT = 'createdat';
    public const UPDATED_AT = 'updatedat';

    protected $connection = 'scheduler';
    protected $table = 'instructor';

    protected $fillable = ['dtdb_id','firstname','lastname','dob','address','address1','city','state','zip',
        'phone_cell','phone_home','email','dl_number','il_number','username','password','usertype','school_id','archived'];

    public function getFullNameAttribute($value)
    {
        return "{$this->lastname}, {$this->firstname}";
    }
}
