<?php

namespace App\Livewire\Forms;

use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EnrollmentForm extends Form
{
    public Student $student;
    public ?int $seminar = null;
    public ?float $discount = null;
    public ?string $level = null;
    public ?float $tuition = null;

    public function rules(): array
    {
        return [
            'seminar_id' => ['required', 'integer', 'exists:seminars,id'],
            'discount' => ['required', 'numeric', 'min:0'],
            'level' => ['required', 'string', 'max:255'],
            'tuition' => ['required', 'numeric', 'min:0'],
        ];
    }
}
