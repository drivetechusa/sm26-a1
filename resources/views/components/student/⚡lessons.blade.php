<?php

use App\Models\Lesson;
use App\Models\Student;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    use \Livewire\WithPagination;
    public Student $student;

    #[Computed]
    public function lessons()
    {
        $query = Lesson::query()->where('student_id', $this->student->id);
        return $query->get();
    }

    #[Computed]
    public function scheduledLessons()
    {
        $query = \App\Models\Scheduler\Lesson::query()->where('student_id', $this->student->stu_web_id)->where('complete', false);
        return $query->orderBy('start_time','asc')->get();
    }
};
?>

<div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Start</flux:table.column>
            <flux:table.column>End</flux:table.column>
            <flux:table.column>Total Hrs</flux:table.column>
            <flux:table.column>Start Miles</flux:table.column>
            <flux:table.column>End Miles</flux:table.column>
            <flux:table.column>Total Miles</flux:table.column>
            <flux:table.column>Instructor</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->scheduledLessons as $scheduledLesson)
                <livewire:rows.scheduled-lessons :lesson="$scheduledLesson" :key="$scheduledLesson->id"/>
            @endforeach
            @foreach ($this->lessons as $lesson)
                <livewire:rows.lesson :lesson="$lesson" :key="$lesson->id"/>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
