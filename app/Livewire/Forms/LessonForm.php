<?php

namespace App\Livewire\Forms;

use App\Models\Lesson;
use Livewire\Form;

class LessonForm extends Form
{
    public ?Lesson $lesson = null;

    public ?int $student_id = null;

    public ?string $type = null;

    public ?int $employee_id = null;

    public $date = null;

    public ?string $start_time = null;

    public ?string $end_time = null;

    public ?int $created_by = null;

    public ?int $updated_by = null;

    public ?int $vehicle_id = null;

    public ?float $begin_mileage = null;

    public ?float $end_mileage = null;
    public ?bool $complete = true;

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'type' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'begin_mileage' => ['nullable', 'numeric', 'min:0'],
            'end_mileage' => ['nullable', 'numeric', 'min:0', 'gte:begin_mileage'],
        ];
    }

    public function setLesson(Lesson $lesson): void
    {
        $this->lesson = $lesson;

        $this->student_id = $lesson->student_id;
        $this->type = $lesson->type;
        $this->employee_id = $lesson->employee_id;
        $this->start_time = $lesson->start_time?->format('H:i');
        $this->end_time = $lesson->end_time?->format('H:i');
        $this->date = $lesson->start_time?->format('Y-m-d');
        $this->complete = $lesson->complete;
        $this->created_by = $lesson->created_by;
        $this->updated_by = $lesson->updated_by;
        $this->vehicle_id = $lesson->vehicle_id;
        $this->begin_mileage = $lesson->begin_mileage;
        $this->end_mileage = $lesson->end_mileage;
    }

    public function store(): Lesson
    {
        $validated = $this->validate();
        $this->start_time = $this->date . ' ' . $this->start_time;
        $this->end_time = $this->date . ' ' . $this->end_time;
        $this->updated_by = auth()->id();
        $this->created_by = auth()->id();
        return Lesson::create($this->only([
            'student_id', 'type', 'employee_id', 'start_time',
            'end_time', 'complete',
            'created_by', 'updated_by', 'vehicle_id', 'begin_mileage',
            'end_mileage',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->lesson->update($this->only([
            'student_id', 'type', 'employee_id', 'sessionnotes', 'start_time',
            'end_time', 'zone_id', 'pulocation_id', 'complete', 'hide',
            'created_by', 'updated_by', 'vehicle_id', 'begin_mileage',
            'end_mileage', 'lesson_number',
        ]));
    }
}
