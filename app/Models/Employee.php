<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Employee extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
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
}
