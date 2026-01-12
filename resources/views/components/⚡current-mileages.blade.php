<?php

use App\Models\Vehicle;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function vehicles()
    {
        $query = Vehicle::query()->where('active', true);
        return $query->get();
    }


};
?>

<div class="py-2 px-3">
    <h1>Current Mileages</h1>
    <ul role="list" class="divide-y divide-gray-100">
        @foreach ($this->vehicles as $vehicle)
        <li class="flex items-center justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-base font-semibold text-gray-900 dark:text-zinc-300">{{$vehicle->name}}</p>

                </div>
            </div>
            <div>
                {{$vehicle->current_mileage}}
            </div>
            <a href="#" class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50">View</a>
        </li>
        @endforeach
    </ul>
</div>
