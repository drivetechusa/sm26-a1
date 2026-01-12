<?php

namespace App\Livewire\Forms;

use App\Models\Tpttest;
use Livewire\Form;

class TestingForm extends Form
{
    public ?Tpttest $tpttest = null;

    public ?int $student_id = null;

    public ?string $date = null;

    public ?bool $walk_in = false;

    public ?bool $substitute = false;

    public ?bool $complete = false;

    public ?string $test_type = null;

    public ?string $route = null;

    public ?int $so_id = null;

    public ?int $test_id = null;

    public ?string $status = null;

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'date' => ['required', 'date'],
            'walk_in' => ['nullable', 'boolean'],
            'substitute' => ['nullable', 'boolean'],
            'complete' => ['nullable', 'boolean'],
            'test_type' => ['nullable', 'string', 'max:255'],
            'route' => ['required', 'string', 'max:255'],
            'so_id' => ['nullable', 'integer'],
            'test_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function setTpttest(Tpttest $tpttest): void
    {
        $this->tpttest = $tpttest;

        $this->student_id = $tpttest->student_id;
        $this->date = $tpttest->date?->format('Y-m-d');
        $this->walk_in = $tpttest->walk_in;
        $this->substitute = $tpttest->substitute;
        $this->complete = $tpttest->complete;
        $this->test_type = $tpttest->test_type;
        $this->route = $tpttest->route;
        $this->so_id = $tpttest->so_id;
        $this->test_id = $tpttest->test_id;
        $this->status = $tpttest->status;
    }

    public function store(): Tpttest
    {
        $this->validate();

        return Tpttest::create($this->only([
            'student_id',
            'date',
            'walk_in',
            'substitute',
            'complete',
            'test_type',
            'route',
            'so_id',
            'test_id',
            'status',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->tpttest->update($this->only([
            'student_id',
            'date',
            'walk_in',
            'substitute',
            'complete',
            'test_type',
            'route',
            'so_id',
            'test_id',
            'status',
        ]));
    }
}
