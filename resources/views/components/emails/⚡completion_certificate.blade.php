<?php

use App\Models\Letter;
use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;
    public Letter $letter;

    public function mount($id)
    {
        $this->student = Student::find($id);
        $this->letter = Letter::find(5);
    }

    public function sendCompletionCertificate()
    {
        \Illuminate\Support\Facades\Mail::to($this->student->notification_emails)->send(new \App\Mail\CompletionCertificate($this->student, $this->letter));
        Flux::toast('Completion Certificate has been sent.');
        $this->student->notes()->create([
            'note' => 'Completion certificate emailed.',
            'instructor_id' => auth()->id(),
            'updated_by' => auth()->id()
        ]);
        $this->dispatch('note-added')->to(ref: 'student-notes-table');
    }
};
?>

<flux:navmenu.item wire:click="sendCompletionCertificate">Completion Certificate</flux:navmenu.item>
