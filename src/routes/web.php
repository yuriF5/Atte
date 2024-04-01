<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Work_timeController;
use App\Http\Controllers\Rest_timeController;
use App\Http\Controllers\MiddlewareController;

// ログイン中で勤怠打刻
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [RegisteredUserController::class, 'index']); 
    Route::post('/start', [Work_timeController::class,'create'])->name('work_time/start');
    Route::post('/finish', [Work_timeController::class,'store'])->name('work_time/finish');
    Route::post('/startRest', [Work_timeController::class,'startRest'])->name('startRest');
    Route::post('/finishRest', [Work_timeController::class,'finishRest'])->name('finishRest');
    Route::get('/attendance', [Work_timeController::class, 'attendance']);
    
});
// ログアウト
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// 日別一覧previewボタン用
Route::post('/attendance/date', [AttendanceController::class, 'perDate'])
    ->name('per/date');