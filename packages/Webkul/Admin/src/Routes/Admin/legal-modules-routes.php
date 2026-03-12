<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Deadline\DeadlineController;
use Webkul\Admin\Http\Controllers\Document\DocumentController;
use Webkul\Admin\Http\Controllers\Hearing\HearingController;
use Webkul\Admin\Http\Controllers\TimeEntry\TimeEntryController;

/**
 * Hearing routes (Audiências).
 */
Route::controller(HearingController::class)->prefix('hearings')->group(function () {
    Route::get('', 'index')->name('admin.hearings.index');

    Route::get('create', 'create')->name('admin.hearings.create');

    Route::post('create', 'store')->name('admin.hearings.store');

    Route::get('view/{id}', 'view')->name('admin.hearings.view');

    Route::put('edit/{id}', 'update')->name('admin.hearings.update');

    Route::delete('{id}', 'destroy')->name('admin.hearings.delete');
});

/**
 * Document routes (Documentos Jurídicos).
 */
Route::controller(DocumentController::class)->prefix('documents')->group(function () {
    Route::get('', 'index')->name('admin.documents.index');

    Route::get('create', 'create')->name('admin.documents.create');

    Route::post('create', 'store')->name('admin.documents.store');

    Route::get('view/{id}', 'view')->name('admin.documents.view');

    Route::get('download/{id}', 'download')->name('admin.documents.download');

    Route::put('edit/{id}', 'update')->name('admin.documents.update');

    Route::delete('{id}', 'destroy')->name('admin.documents.delete');
});

/**
 * Time entry routes (Registo de Horas).
 */
Route::controller(TimeEntryController::class)->prefix('time-entries')->group(function () {
    Route::get('', 'index')->name('admin.time-entries.index');

    Route::get('create', 'create')->name('admin.time-entries.create');

    Route::post('create', 'store')->name('admin.time-entries.store');

    Route::put('edit/{id}', 'update')->name('admin.time-entries.update');

    Route::post('mark-billed', 'markBilled')->name('admin.time-entries.mark_billed');

    Route::delete('{id}', 'destroy')->name('admin.time-entries.delete');
});

/**
 * Deadline routes (Prazos Processuais).
 */
Route::controller(DeadlineController::class)->prefix('deadlines')->group(function () {
    Route::get('', 'index')->name('admin.deadlines.index');

    Route::get('calendar', 'calendar')->name('admin.deadlines.calendar');

    Route::get('create', 'create')->name('admin.deadlines.create');

    Route::post('create', 'store')->name('admin.deadlines.store');

    Route::put('edit/{id}', 'update')->name('admin.deadlines.update');

    Route::post('calculate-due-date', 'calculateDueDate')->name('admin.deadlines.calculate_due_date');

    Route::delete('{id}', 'destroy')->name('admin.deadlines.delete');
});
