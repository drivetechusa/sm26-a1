<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\WebNotification;
use Livewire\Volt\Volt;

test('notifications index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertOk();
});

test('notifications index requires authentication', function () {
    $this->get(route('notifications.index'))
        ->assertRedirect(route('login'));
});

test('a new notification can be created', function () {
    $user = User::factory()->create();

    Volt::test('pages.notifications.index')
        ->actingAs($user)
        ->set('message', 'Test notification message')
        ->set('isActive', true)
        ->call('createNotification')
        ->assertHasNoErrors();

    expect(WebNotification::where('message', 'Test notification message')->where('is_active', true)->exists())->toBeTrue();
});

test('notification creation requires a message', function () {
    $user = User::factory()->create();

    Volt::test('pages.notifications.index')
        ->actingAs($user)
        ->set('message', '')
        ->call('createNotification')
        ->assertHasErrors(['message' => 'required']);
});

test('form is reset after creating a notification', function () {
    $user = User::factory()->create();

    Volt::test('pages.notifications.index')
        ->actingAs($user)
        ->set('message', 'Reset test message')
        ->call('createNotification')
        ->assertSet('message', '');
});

test('an inactive notification can be deleted', function () {
    $user = User::factory()->create();
    $notification = WebNotification::factory()->create(['is_active' => false]);

    Volt::test('rows.notification', ['notification' => $notification])
        ->actingAs($user)
        ->call('remove')
        ->assertHasNoErrors();

    expect(WebNotification::find($notification->id))->toBeNull();
});

test('an active notification cannot be deleted', function () {
    $user = User::factory()->create();
    $notification = WebNotification::factory()->create(['is_active' => true]);

    Volt::test('rows.notification', ['notification' => $notification])
        ->actingAs($user)
        ->call('remove')
        ->assertForbidden();

    expect(WebNotification::find($notification->id))->not->toBeNull();
});
