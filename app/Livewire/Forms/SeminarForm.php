<?php

namespace App\Livewire\Forms;

use App\Models\Seminar;
use Livewire\Form;

class SeminarForm extends Form
{
    public ?Seminar $seminar = null;

    public ?int $classroom_id = null;

    public ?int $employee_id = null;

    public ?string $date = null;


    public ?bool $full = false;

    public ?string $class_type = null;

    public ?bool $cancelled = false;

    public ?string $add_info = null;

    public ?float $sale_price = null;

    public function rules(): array
    {
        return [
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'full' => ['required', 'boolean'],
            'class_type' => ['required', 'string', 'max:255'],
            'cancelled' => ['required', 'boolean'],
            'add_info' => ['nullable', 'string'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function setSeminar(Seminar $seminar): void
    {
        $this->seminar = $seminar;

        $this->classroom_id = $seminar->classroom_id;
        $this->employee_id = $seminar->employee_id;
        $this->date = $seminar->date?->format('Y-m-d');
        $this->full = $seminar->full;
        $this->class_type = $seminar->class_type;
        $this->cancelled = $seminar->cancelled;
        $this->add_info = $seminar->add_info;
        $this->sale_price = $seminar->sale_price;
    }

    public function store(): Seminar
    {
        $this->validate();

        return Seminar::create($this->only([
            'classroom_id',
            'employee_id',
            'date',
            'full',
            'class_type',
            'cancelled',
            'add_info',
            'sale_price',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->seminar->update($this->only([
            'classroom_id',
            'employee_id',
            'date',
            'full',
            'class_type',
            'cancelled',
            'add_info',
            'sale_price',
        ]));
    }
}
