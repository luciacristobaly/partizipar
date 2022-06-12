<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ListUsersController;

/*
 * GET - Request a resource
 * POST - Create a new resource
 * PUT - Update a resource (every single value in it)
 * PATCH - Modify a resource
 * DELETE - Delete a resource
 */

Route::get('/', function() {
    $route = app()->getLocale() == '' ? 'es' : app()->getLocale();
    return redirect($route);
});


Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => 'setLocale',
], function() {

    // GET
    Route::get('/', [MeetingController::class, 'index'])->name('home');
    Route::get('/meeting/{id}', [MeetingController::class, 'show'])->name('meeting.show');

    Route::get('/lectures', [LectureController::class, 'index'])->name('lectures');
    Route::get('/lecture/{id}', [LectureController::class, 'show'])->name('lecture.show');

    Route::get('/lists', [ListUsersController::class, 'index'])->name('lists');
    Route::get('/lists/{id}', [ListUsersController::class, 'show'])->name('lists.show');

    // POST
    Route::get('/createMeeting', [MeetingController::class, 'create'])->name('meeting.create');
    Route::post('/home', [MeetingController::class, 'store'])->name('meeting.store');

    Route::get('/createLecture', [LectureController::class, 'create'])->name('lecture.create');
    Route::post('/lectures', [LectureController::class, 'store'])->name('lecture.store');

    Route::get('/createList', [ListUsersController::class, 'create'])->name('list.create');
    Route::post('/lists', [ListUsersController::class, 'store'])->name('list.store');

    // PUT or patch
    Route::get('/meeting/edit/{id}', [MeetingController::class, 'edit'])->name('meeting.edit');
    Route::get('/meetings/{id}', [MeetingController::class, 'update'])->name('meeting.update');

    Route::get('/lectures/edit/{id}', [LectureController::class, 'edit'])->name('lecture.edit');
    Route::get('/lectures/{id}', [LectureController::class, 'update'])->name('lecture.update');

    Route::get('/list/edit/{id}', [ListUsersController::class, 'edit'])->name('list.edit');
    Route::get('/list/update/{id}', [ListUsersController::class, 'update'])->name('list.update');

    // DELETE
    Route::get('/meetings/{id}/delete', [MeetingController::class, 'destroy'])->name('meeting.delete');
    Route::get('/lectures/{id}/delete', [LectureController::class, 'destroy'])->name('lecture.delete');
    Route::get('/lists/{id}/delete', [ListUsersController::class, 'destroy'])->name('list.delete');

    Route::get('/login', [MainController::class, 'index'])->name('login');
});


