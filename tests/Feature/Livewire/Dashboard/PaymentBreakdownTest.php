<?php

declare(strict_types=1);

use App\Models\Payment;
use Illuminate\Support\Carbon;
use Livewire\Volt\Volt;

beforeEach(function () {
    Carbon::setTestNow('2025-12-10 12:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

test('component renders successfully', function () {
    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertOk()
        ->assertSee('Payment Breakdown - Last 7 Days');
});

test('displays total collected amount', function () {
    Payment::factory()->create([
        'amount' => 100.00,
        'date' => Carbon::now()->subDays(2),
    ]);

    Payment::factory()->create([
        'amount' => 250.50,
        'date' => Carbon::now()->subDays(4),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('$350.50')
        ->assertSee('Total Collected');
});

test('displays total payment count', function () {
    Payment::factory()->count(5)->create([
        'date' => Carbon::now()->subDays(3),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('5')
        ->assertSee('Total Payments');
});

test('displays average payment amount', function () {
    Payment::factory()->create([
        'amount' => 100.00,
        'date' => Carbon::now()->subDays(1),
    ]);

    Payment::factory()->create([
        'amount' => 200.00,
        'date' => Carbon::now()->subDays(2),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('$150.00')
        ->assertSee('Average Payment');
});

test('only includes payments from last 7 days', function () {
    Payment::factory()->create([
        'amount' => 100.00,
        'date' => Carbon::now()->subDays(5),
    ]);

    Payment::factory()->create([
        'amount' => 200.00,
        'date' => Carbon::now()->subDays(10),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('$100.00')
        ->assertDontSee('$200.00')
        ->assertDontSee('$300.00');
});

test('displays daily breakdown', function () {
    Payment::factory()->count(2)->create([
        'amount' => 50.00,
        'date' => Carbon::now()->subDays(2),
    ]);

    Payment::factory()->create([
        'amount' => 75.00,
        'date' => Carbon::now()->subDays(3),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('Daily Breakdown')
        ->assertSee('$100.00')
        ->assertSee('$75.00');
});

test('displays payment type breakdown', function () {
    Payment::factory()->count(2)->create([
        'amount' => 100.00,
        'type' => 'cash',
        'date' => Carbon::now()->subDays(1),
    ]);

    Payment::factory()->create([
        'amount' => 150.00,
        'type' => 'credit',
        'date' => Carbon::now()->subDays(2),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('Payment Type Breakdown')
        ->assertSee('Cash')
        ->assertSee('$200.00')
        ->assertSee('Credit')
        ->assertSee('$150.00');
});

test('handles empty state when no payments exist', function () {
    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('$0.00')
        ->assertSee('0')
        ->assertSee('No payments in the last 7 days');
});

test('correctly formats dates in daily breakdown', function () {
    Payment::factory()->create([
        'amount' => 100.00,
        'date' => Carbon::parse('2025-12-08 14:30:00'),
    ]);

    Volt::test('livewire.dashboard.payment-breakdown')
        ->assertSee('Dec 08, 2025');
});

test('orders daily breakdown by date descending', function () {
    Payment::factory()->create([
        'amount' => 50.00,
        'date' => Carbon::now()->subDays(5),
    ]);

    Payment::factory()->create([
        'amount' => 75.00,
        'date' => Carbon::now()->subDays(2),
    ]);

    Payment::factory()->create([
        'amount' => 100.00,
        'date' => Carbon::now()->subDays(1),
    ]);

    $component = Volt::test('livewire.dashboard.payment-breakdown');

    $dailyBreakdown = $component->get('dailyBreakdown');

    expect($dailyBreakdown[0]['date'])->toBe('Dec 09, 2025');
    expect($dailyBreakdown[1]['date'])->toBe('Dec 08, 2025');
    expect($dailyBreakdown[2]['date'])->toBe('Dec 05, 2025');
});

test('orders type breakdown by total descending', function () {
    Payment::factory()->create([
        'amount' => 50.00,
        'type' => 'cash',
        'date' => Carbon::now()->subDays(1),
    ]);

    Payment::factory()->create([
        'amount' => 200.00,
        'type' => 'credit',
        'date' => Carbon::now()->subDays(2),
    ]);

    Payment::factory()->create([
        'amount' => 100.00,
        'type' => 'check',
        'date' => Carbon::now()->subDays(3),
    ]);

    $component = Volt::test('livewire.dashboard.payment-breakdown');

    $typeBreakdown = $component->get('typeBreakdown');

    expect($typeBreakdown[0]['type'])->toBe('Credit');
    expect($typeBreakdown[1]['type'])->toBe('Check');
    expect($typeBreakdown[2]['type'])->toBe('Cash');
});
