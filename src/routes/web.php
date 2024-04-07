<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Work_timeController;
use App\Http\Controllers\Rest_timeController;
use App\Http\Controllers\MiddlewareController;

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [RegisteredUserController::class, 'index']); 
    Route::post('/start', [Work_timeController::class,'create'])->name('work_time/start');
    Route::post('/finish', [Work_timeController::class,'store'])->name('work_time/finish');
    Route::post('/startRest', [Work_timeController::class,'startRest'])->name('startRest');
    Route::post('/finishRest', [Work_timeController::class,'finishRest'])->name('finishRest');
    Route::get('/attendance', [Work_timeController::class, 'attendance']);
});
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::post('/attendance/date', [Work_timeController::class, 'perDate'])
    ->name('per/date');
Route::post('/register',[RegisteredUserController::class, 'store']);