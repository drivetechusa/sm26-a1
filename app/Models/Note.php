<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'last_update';

    public function casts()
    {
        return [
            'created' => 'datetime',
            'last_update' => 'datetime',
        ];
    }
}
