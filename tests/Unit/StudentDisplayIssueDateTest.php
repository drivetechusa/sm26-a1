<?php

use App\Models\Student;

uses(Tests\TestCase::class);

it('formats the issue date without trailing data errors', function () {
    $student = new Student;
    $student->issue_date = '2024-01-15';

    expect($student->display_issue_date)->toBe('01/15/2024');
});

it('returns an empty string when the issue date is null', function () {
    $student = new Student;
    $student->issue_date = null;

    expect($student->display_issue_date)->toBe('');
});
