<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function deleteStudent()
    {
        $this->student->delete();
        return $this->redirectRoute('dashboard');
    }
}
?>

<div>
    <flux:modal.trigger :name="'delete-student-' . $student->id">
        <flux:navmenu.item icon="shield-check">Delete Student</flux:navmenu.item>
    </flux:modal.trigger>

    @teleport('body')
    <flux:modal :name="'delete-student-' . $student->id" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete student?</flux:heading>
                <flux:text class="mt-2">
                    You're about to delete this student.<br>
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteStudent" variant="danger">Delete student</flux:button>
            </div>
        </div>
    </flux:modal>
    @endteleport
</div>
