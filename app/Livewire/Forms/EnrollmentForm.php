<?php

namespace App\Livewire\Forms;

use App\Models\Seminar;
use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EnrollmentForm extends Form
{
    public ?Student $student;
    public ?int $seminar_id = null;
    public ?float $discount = null;
    public ?string $level = null;
    public ?float $tuition = null;

    public function rules(): array
    {
        return [
            'seminar_id' => ['required', 'integer', 'exists:classes,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'level' => ['required', 'string', 'max:255'],
            'tuition' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function store()
    {
        $this->validate();
        $seminar = Seminar::find($this->seminar_id);
        $this->student->seminars()
            ->attach($seminar, ['discount' => $this->discount, 'level' => $this->level, 'tuition' => $this->tuition]);
    }
}
