<?php

use App\Livewire\Forms\StudentForm;

it('normalizes empty date strings to null before save', function () {
    $form = new StudentForm(new Livewire\Component, 'form');

    $form->dob = '';
    $form->date_started = '';
    $form->date_completed = '';
    $form->issue_date = '';
    $form->renewal_date = '';

    $method = new ReflectionMethod($form, 'normalizeDates');
    $method->setAccessible(true);
    $method->invoke($form);

    expect($form->dob)->toBeNull();
    expect($form->date_started)->toBeNull();
    expect($form->date_completed)->toBeNull();
    expect($form->issue_date)->toBeNull();
    expect($form->renewal_date)->toBeNull();
});

it('preserves populated date strings', function () {
    $form = new StudentForm(new Livewire\Component, 'form');

    $form->date_completed = '2026-04-01';

    $method = new ReflectionMethod($form, 'normalizeDates');
    $method->setAccessible(true);
    $method->invoke($form);

    expect($form->date_completed)->toBe('2026-04-01');
});
