<?php

use App\Models\Payment;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    #[Computed]
    public function totalAmount(): float
    {
        return Payment::query()
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->sum('amount');
    }

    #[Computed]
    public function dailyBreakdown(): array
    {
        $payments = Payment::query()
            ->selectRaw('DATE(date) as payment_date, SUM(amount) as total, COUNT(*) as count')
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->groupBy('payment_date')
            ->orderBy('payment_date', 'desc')
            ->get();

        return $payments->map(function ($payment) {
            return [
                'date' => Carbon::parse($payment->payment_date)->format('M d, Y'),
                'total' => number_format($payment->total, 2),
                'count' => $payment->count,
            ];
        })->toArray();
    }

    #[Computed]
    public function typeBreakdown(): array
    {
        $payments = Payment::query()
            ->selectRaw('type, SUM(amount) as total, COUNT(*) as count')
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get();

        return $payments->map(function ($payment) {
            return [
                'type' => ucfirst($payment->type ?? 'Unknown'),
                'total' => number_format($payment->total, 2),
                'count' => $payment->count,
            ];
        })->toArray();
    }

    #[Computed]
    public function paymentCount(): int
    {
        return Payment::query()
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->count();
    }
}; ?>

<div class="space-y-6">
    <flux:card>
        <flux:heading size="lg" class="mb-4">Payment Breakdown - Last 7 Days</flux:heading>

        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <div class="rounded-lg bg-neutral-50 p-4 dark:bg-neutral-800">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Collected</div>
                <div class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">${{ number_format($this->totalAmount, 2) }}</div>
            </div>
            <div class="rounded-lg bg-neutral-50 p-4 dark:bg-neutral-800">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Payments</div>
                <div class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ number_format($this->paymentCount) }}</div>
            </div>
            <div class="rounded-lg bg-neutral-50 p-4 dark:bg-neutral-800">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Average Payment</div>
                <div class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                    ${{ $this->paymentCount > 0 ? number_format($this->totalAmount / $this->paymentCount, 2) : '0.00' }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <flux:heading size="md" class="mb-3">Daily Breakdown</flux:heading>
                @if (count($this->dailyBreakdown) > 0)
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Date</flux:table.column>
                            <flux:table.column>Payments</flux:table.column>
                            <flux:table.column>Total</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($this->dailyBreakdown as $day)
                                <flux:table.row>
                                    <flux:table.cell>{{ $day['date'] }}</flux:table.cell>
                                    <flux:table.cell>{{ $day['count'] }}</flux:table.cell>
                                    <flux:table.cell class="font-semibold">${{ $day['total'] }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @else
                    <flux:text class="text-neutral-500 dark:text-neutral-400">No payments in the last 7 days</flux:text>
                @endif
            </div>

            <div>
                <flux:heading size="md" class="mb-3">Payment Type Breakdown</flux:heading>
                @if (count($this->typeBreakdown) > 0)
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Type</flux:table.column>
                            <flux:table.column>Payments</flux:table.column>
                            <flux:table.column>Total</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($this->typeBreakdown as $type)
                                <flux:table.row>
                                    <flux:table.cell>{{ $type['type'] }}</flux:table.cell>
                                    <flux:table.cell>{{ $type['count'] }}</flux:table.cell>
                                    <flux:table.cell class="font-semibold">${{ $type['total'] }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @else
                    <flux:text class="text-neutral-500 dark:text-neutral-400">No payments in the last 7 days</flux:text>
                @endif
            </div>
        </div>
    </flux:card>
</div>
