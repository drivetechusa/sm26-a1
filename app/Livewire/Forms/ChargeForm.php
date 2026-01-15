<?php

namespace App\Livewire\Forms;

use App\Models\Charge;
use Livewire\Form;

class ChargeForm extends Form
{
    public ?Charge $charge = null;

    public ?int $student_id = null;

    public ?float $amount = null;

    public $entered = null;

    public ?string $reason = null;

    public ?int $updated_by = null;

    public ?int $employee_id = null;

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'entered' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ];
    }

    public function setCharge(Charge $charge): void
    {
        $this->charge = $charge;

        $this->student_id = $charge->student_id;
        $this->amount = $charge->amount;
        $this->entered = $charge->entered?->format('Y-m-d');
        $this->reason = $charge->reason;
        $this->updated_by = $charge->updated_by;
        $this->employee_id = $charge->employee_id;
    }

    public function store(): Charge
    {
        $this->validate();
        $this->employee_id = auth()->id();
        $this->updated_by = auth()->id();

        return Charge::create($this->only([
            'student_id', 'amount', 'entered', 'reason',
            'updated_by', 'employee_id',
        ]));
    }

    public function update(): void
    {
        $this->validate();
        $this->updated_by = auth()->id();

        $this->charge->update($this->only([
            'amount', 'reason', 'updated_by',
        ]));
    }
}
