<?php

use App\Models\Scheduler\Zone;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public Student $student;
    #[Validate('required')]
    public $zone_id = null;

    public function mount($id)
    {
        $this->student = Student::find($id);
        $this->zone_id = $this->student->zone_id;
    }

    public function showModal()
    {
        Flux::modal('assign-zone')->show();
    }

    public function assignZone()
    {
        $this->validate();
        $this->student->zone_id = $this->zone_id;
        $this->student->save();

        $this->dispatch("student-updated.{$this->student->id}");
        Flux::modals()->close();
    }

    #[Computed]
    public function zones()
    {
        $query = Zone::query()->where('archived', false);
        return $query->get();
    }
};
?>

<div>
    <flux:navmenu.item icon="map-pin" wire:click="showModal">Zone Override</flux:navmenu.item>
    @teleport('body')
    <flux:modal name="assign-zone" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Assign Zone</flux:heading>

            </div>
            <form wire:submit="assignZone" class="space-y-4">
                <flux:select label="Zone" wire:model="zone_id">
                    <option value="">Select Zone...</option>
                    @foreach ($this->zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </flux:select>
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Assign Zone</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    @endteleport
</div>
