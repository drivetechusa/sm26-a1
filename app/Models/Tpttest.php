<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tpttest extends Model
{
    /** @use HasFactory<\Database\Factories\TpttestFactory> */
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'walk_in', 'substitute', 'complete', 'test_type','route','so_id','test_id','status'];

    protected $table = 'tpttests';

    public function casts()
    {
        return [
            'date' => 'datetime',
            'walk_in' => 'boolean',
            'substitute' => 'boolean',
            'complete' => 'boolean',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
