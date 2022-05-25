<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Models\Meeting;
use app\Models\Lecture;
use app\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Meetings
Route::get('meetings', 'MeetingController@index');
Route::get('meeting/{id}', 'MeetingController@show');
Route::post('articlemeetings', 'MeetingController@store');
Route::put('meeting/{id}', 'MeetingController@update');
Route::delete('meeting/{id}', 'MeetingController@delete');

//Lectures
Route::get('/lectures', 'LectureController@index');
Route::get('lectures/{id}', 'LectureController@show');
Route::post('articlelecture', 'LectureController@store');
Route::put('lectures/{id}', 'LectureController@update');
Route::delete('lectures/{id}', 'LectureController@delete');