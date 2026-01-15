<?php

namespace App\Livewire\Forms;

use App\Models\Payment;
use Livewire\Form;

class PaymentForm extends Form
{
    public ?Payment $payment = null;

    public ?int $student_id = null;

    public ?float $amount = null;

    public $date = null;

    public ?string $type = null;

    public ?string $check_number = null;

    public ?string $auth_number = null;

    public ?int $employee_id = null;

    public $last_update = null;

    public ?int $updated_by = null;

    public ?string $remarks = null;


    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255'],
            'check_number' => ['nullable', 'string', 'max:255'],
            'auth_number' => ['nullable', 'string', 'max:255'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;

        $this->student_id = $payment->student_id;
        $this->amount = $payment->amount;
        $this->date = $payment->date?->format('Y-m-d');
        $this->type = $payment->type;
        $this->check_number = $payment->check_number;
        $this->auth_number = $payment->auth_number;
        $this->employee_id = $payment->employee_id;
        $this->last_update = $payment->last_update;
        $this->updated_by = $payment->updated_by;
        $this->remarks = $payment->remarks;
    }

    public function store(): Payment
    {
        $this->validate();
        $this->updated_by = auth()->id();
        $this->last_update = now();

        return Payment::create($this->only([
            'student_id', 'amount', 'date', 'type', 'check_number',
            'auth_number', 'employee_id', 'last_update', 'updated_by',
            'remarks',
        ]));
    }

    public function update(): void
    {
        $this->validate();
        $this->updated_by = auth()->id();
        $this->last_update = now();

        $this->payment->update($this->only([
            'amount', 'date', 'type', 'check_number', 'auth_number',
            'last_update', 'updated_by', 'remarks',
        ]));
    }
}
