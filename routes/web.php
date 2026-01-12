<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::livewire('/employees', 'pages::employees.index')->name('employees.index');
    Route::livewire('/vehicles', 'pages::vehicles.index')->name('vehicles.index');
    Route::livewire('/classrooms', 'pages::classrooms.index')->name('classrooms.index');

    Route::livewire('/seminars', 'pages::seminars.index')->name('seminars.index');
    Route::livewire('/seminars/create', 'pages::seminars.create')->name('seminars.create');
    Route::livewire('/seminars/{id}/edit', 'pages::seminars.edit')->name('seminars.edit');

    Route::livewire('/reports', 'pages::reports.index')->name('reports.index');
    Route::livewire('/zipcodes', 'pages::zipcodes.index')->name('zipcodes.index');
    Route::livewire('/notifications', 'pages::notifications.index')->name('notifications.index');
    Route::livewire('/letters', 'pages::letters.index')->name('letters.index');
    Route::livewire('/vehicles/{vehicle}', 'pages::vehicles.show')->name('vehicles.show');
    Route::livewire('/employees/{employee}', 'pages::employees.show')->name('employees.show');
    Route::livewire('/classrooms/{classroom}', 'pages::classrooms.show')->name('classrooms.show');
    Route::livewire('/students/create', 'pages::students.create')->name('students.create');
    Route::livewire('/students/{id}', 'pages::students.show')->name('students.show');
    Route::livewire('/students/{id}/edit', 'pages::students.edit')->name('students.edit');
});

require __DIR__.'/documents.php';
require __DIR__.'/emails.php';
