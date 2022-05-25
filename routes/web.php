<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\LectureController;

/*
 * GET - Request a resource
 * POST - Create a new resource
 * PUT - Update a resource (every single value in it)
 * PATCH - Modify a resource
 * DELETE - Delete a resource
 */


 App::setLocale('es');


 // GET
Route::get('/home', [MeetingController::class, 'index'])->name('home');
Route::get('/meeting/{id}', [MeetingController::class, 'show'])->name('meeting.show');

Route::get('/lectures', [LectureController::class, 'index'])->name('lectures');
Route::get('/lectures/{id}', [LectureController::class, 'show'])->name('lectures')->whereNumber('id');

// POST
Route::get('/createMeeting', [MeetingController::class, 'create'])->name('meeting.create');
Route::post('/home', [MeetingController::class, 'store'])->name('meeting.store');

Route::get('/createLecture', [LectureController::class, 'create'])->name('lecture.create');
Route::post('/lectures', [LectureController::class, 'store'])->name('lecture.store');

// PUT or patch
Route::get('/meeting/edit/{id}', [MeetingController::class, 'edit'])->name('meeting.edit');
Route::patch('/meeting/{id}', [MeetingController::class, 'update'])->name('meeting.update');

// DELETE
Route::delete('/meeting/{id}', [MeetingController::class, 'destroy'])->name('meeting.delete');


