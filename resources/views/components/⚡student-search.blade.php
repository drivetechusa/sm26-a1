<?php

use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    //protected $paginationTheme = 'tailwindcss';

    public $filters = [
        'firstname' => '',
        'lastname' => '',
        'phone' => '',
        'email' => '',
        'street' => '',
        'status' => '',
    ];

    #[Computed]
    public function students()
    {
        $query = Student::query()
            ->when($this->filters['firstname'], fn($query, $firstname) => $query->where('firstname', 'like', '%'.$firstname.'%'))
            ->when($this->filters['lastname'], fn($query, $lastname) => $query->where('lastname', 'like', '%'.$lastname.'%'))
            ->when($this->filters['street'], fn($query, $street) => $query->where('street', 'like', '%'.$street.'%'))
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', 'like', '%'.$status.'%'))
            ->when($this->filters['email'], fn($query, $email) => $query->whereAny(['email','email_student','guardian_2_email'], 'like', '%'.$email.'%'))
            ->when($this->filters['phone'], fn ($query, $phone) => $query->whereAny(['phone','student_phone','secondary_phone'], 'like', '%'.$phone.'%'));

            return $query->paginate(10);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }
};
?>

<div>
    <flux:modal.trigger name="student-search">
        <flux:button>Student Search</flux:button>
    </flux:modal.trigger>
    <flux:modal name="student-search" variant="flyout" position="bottom">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Student Search</flux:heading>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <flux:input label="First Name" wire:model.live="filters.firstname"/>
                    <flux:input label="Last Name" wire:model.live="filters.lastname"/>
                    <flux:input label="Phone" wire:model.live="filters.phone"/>
                    <flux:input label="Email" wire:model.live="filters.email"/>
                    <flux:input label="Street Address" wire:model.live="filters.street"/>
                    <flux:input label="Status" wire:model.live="filters.status"/>
                    <div class="flex">
                        <flux:spacer/>
                        <flux:button wire:click="resetFilters" variant="primary">Reset Filters</flux:button>
                    </div>
                </div>
                <div>
                    @foreach ($this->students as $student)
                        <div class="border-b border-gray-200 py-1">
                            <a href="/students/{{$student->id}}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center">
                                    <div class="mr-8">
                                        <div class="text-sm leading-5 font-medium text-indigo-600 truncate ml-2">{{ $student->id }}</div>
                                        <div class="">

                                            <x-status-badge :color="$student->status->color()" :label="$student->status->label()"/>

                                        </div>
                                    </div>
                                    <div class="ml-8">
                                        <div class="text-sm leading-5 font-bold text-indigo-600 truncate ml-2">{{ $student->display_name }}</div>
                                        <div class="text-sm leading-5 font-medium text-indigo-600 truncate ml-2">{{ $student->street }}</div>
                                        @if ($student->zip_id)
                                            <div class="text-sm leading-5 font-medium text-indigo-600 truncate ml-2">{{ $student->csz }}</div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </flux:modal>
</div>
