<?php

use App\Models\Student;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Student $student;
    #[Validate('required', 'numeric')]
    public $status;


    public function mount()
    {
        $this->status = $this->student->status;

    }

    public function updateStatus()
    {
        $this->validate();
        $this->student->status = $this->status;

        $this->student->save();

        Flux::toast('Status Updated.');
        Flux::modals()->close();
        $this->dispatch("student-updated.{$this->student->id}");
    }
};
?>

<div>
    <flux:modal.trigger name="edit-status">
        <flux:button size="sm" variant="primary" color="green">Update Status</flux:button>
    </flux:modal.trigger>

    <flux:modal name="edit-status" class="md:w-96">
        <form wire:submit="updateStatus" class="space-y-6">
            <div>
                <flux:heading size="lg">Update Status</flux:heading>
            </div>
            <flux:select wire:model="status" label="Status">
                @foreach (\App\Enums\StudentStatus::cases() as $status)
                    <flux:select.option :value="$status->value">{{ $status->label() }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer/>

                <flux:button type="submit" variant="primary">Update Status</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
