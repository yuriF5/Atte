<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Work_timeController;
use App\Http\Controllers\Rest_timeController;


//ユーザー新規登録ページ表示
//Route::get('/register', [RegisteredUserController::class, 'create']);
//ユーザー新規登録処理
////Route::post('/register', [RegisteredUserController::class, 'store']);
//ユーザーログイン処理
////Route::get('/login', [AuthenticatedSessionController::class,'store']);
////ユーザーログアウト処理
//Route::post('/login', [AuthenticatedSessionController::class, 'destroy']);

Route::middleware('auth')->group(function () {Route::get('/', [RegisteredUserController::class, 'index']); });
Route::get('/attendance', [Work_timeController::class, 'attendance']);


Route::group(['middleware' => 'auth'], function() {
    Route::post('/start', [Work_timeController::class,'create'])->name('work_time/start');
    Route::post('/finish', [Work_timeController::class,'store'])->name('work_time/finish');
    
});
//Route::post('/r_start', [Rest_timeController::class,'r_start'])->name('rest_time/start')
//Route::post('/r_finish', [Rest_timeController::class,'r_finish'])->name('rest_time/finish')