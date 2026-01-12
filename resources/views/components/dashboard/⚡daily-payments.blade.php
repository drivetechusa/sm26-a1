<?php

use Carbon\Carbon;
use Livewire\Component;

new class extends Component {
    public $date;
    public $begin;
    public $end;

    public function mount($date)
    {
        $this->date = Carbon::createFromFormat('Y-m-d', $date);
        $this->begin = $this->date->copy();
        $this->end = $this->date->copy();
        $this->begin->subDay()->hour(17)->minute(30)->second(0);
        $this->end->hour(17)->minute(29)->second(59);
    }

    #[\Livewire\Attributes\Computed]
    public function payments()
    {
        $query = \App\Models\Payment::query()->whereIn('type', ['Cash','Credit Card','Check']);
        $query->whereBetween('date', [$this->begin, $this->end]);
        return $query->get();
    }
};
?>

<flux:dropdown hover class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
    <button type="button" class="flex items-center gap-2 p-2 rounded-xl">
        <flux:card>
            <flux:heading class="flex items-center gap-2">{{$this->date->format('m/d/Y')}}</flux:heading>
            <span class="text-2xl text-green-700 dark:text-green-400 font-bold">${{$this->payments->sum('amount')}}</span>
        </flux:card>
    </button>

    <flux:popover class="flex flex-col gap-3 rounded-xl shadow-xl">

        <div class="py-2">
            <ul class="space-y-2">
                @foreach ($this->payments as $payment)
                    <li>${{$payment->amount}} - {{$payment->type}} - <a href="{{route('students.show', ['id' => $payment->student_id])}}">{{$payment->student_id}}</a></li>
                @endforeach
            </ul>
        </div>
    </flux:popover>
</flux:dropdown>
