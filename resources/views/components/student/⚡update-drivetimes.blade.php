<?php

use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Student $student;
    #[Validate('required', 'numeric')]
    public $drive_time_purchased;
    #[Validate('required', 'numeric')]
    public $drive_time_completed;

    public function mount()
    {
        $this->drive_time_purchased = $this->student->drive_time_purchased;
        $this->drive_time_completed = $this->student->drive_time_completed;
    }

    public function updateDriveTimes()
    {
        $this->validate();
        $this->student->drive_time_purchased = $this->drive_time_purchased;
        $this->student->drive_time_completed = $this->drive_time_completed;
        $this->student->save();

        Flux::toast('Drive Times Updated.');
        Flux::modals()->close();
        $this->dispatch("student-updated.{$this->student->id}");
    }
};
?>
<div>
    <flux:modal.trigger name="edit-drivetimes">
        <flux:button size="sm" variant="primary" color="green">Update Hours</flux:button>
    </flux:modal.trigger>

    <flux:modal name="edit-drivetimes" class="md:w-96">
        <form wire:submit="updateDriveTimes" class="space-y-6">
            <div>
                <flux:heading size="lg">Update hours</flux:heading>
            </div>

            <flux:input type="number" wire:model="drive_time_purchased" label="Purchased" />
            <flux:input type="number" wire:model="drive_time_completed" label="Completed" />

            <div class="flex">
                <flux:spacer/>

                <flux:button type="submit" variant="primary">Update Hours</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
