<?php

use App\Http\Controllers\DocumentsController;

Route::prefix('documents')->as('documents.')->group(function () {
    Route::get('/roster/{seminar}', [DocumentsController::class, 'print_roster'])->name('roster');
    Route::get('/coversheets/{seminar}', [DocumentsController::class, 'print_class_coversheets'])->name('coversheets');
    Route::get('/class_logs/{seminar}', [DocumentsController::class, 'print_class_logs'])->name('class_logs');
    Route::get('/contracts/{seminar}', [DocumentsController::class, 'print_class_contracts'])->name('contracts');
    Route::get('/roper_invoice', [DocumentsController::class, 'print_roper_invoice'])->name('invoice');
    Route::get('/tpt_report', [DocumentsController::class, 'print_tpt_report'])->name('tpt_report');
    Route::get('/activity_report', [DocumentsController::class, 'print_activity_report'])->name('activity_report');
    Route::get('/dip_certificate/{student}', [DocumentsController::class, 'print_dip_certificate'])->name('dip_certificate');
    Route::get('/dip_letter/{student}', [DocumentsController::class, 'print_dip_letter'])->name('dip_letter');
    Route::get('/beginner_invoice/{student}', [DocumentsController::class, 'print_beginner_invoice'])->name('beginner_invoice');
    Route::get('/documents/quarterly_report', [DocumentsController::class, 'print_quarterly_report'])->name('quarterly_report');
    Route::get('/documents/yearly_report', [DocumentsController::class, 'print_yearly_report'])->name('yearly_report');
    Route::get('/documents/yearly_zipcode_report', [DocumentsController::class, 'print_yearly_zipcode_report'])->name('yearly_zipcode_report');
    Route::get('/documents/workzone_report', [DocumentsController::class, 'print_workzone_report'])->name('workzone_report');
    Route::get('/sc_activity_log/{student}', [DocumentsController::class, 'sc_activity'])->name('sc_activity');
    Route::get('/print_account_statement/{student}', [DocumentsController::class, 'print_account_statement'])->name('print_account_statement');
    Route::get('/print_completion_certificate/{student}', [DocumentsController::class, 'print_completion_certificate'])->name('print_completion_certificate');
    Route::get('/print_instructor_certificate/{student}', [DocumentsController::class, 'print_instructor_certificate'])->name('print_instructor_certificate');
    Route::get('/print_coversheet/{student}', [DocumentsController::class, 'print_coversheet'])->name('print_coversheet');
    Route::get('/print_contract/{student}/{seminar?}', [DocumentsController::class, 'print_contract'])->name('print_contract');
});
