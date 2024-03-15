<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Work_timeController;


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

Route::post('/work/start',[WorkController::class,'work_start'])->name('work.start');
Route::post('/work/finish',[WorkController::class,'work_finish'])->name('work.finish');