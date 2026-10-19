<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Middleware\SchoolContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth');
Route::get('/ready', function () {
    try {
        DB::select('select 1');
        Redis::ping();

        return response()->json(['status' => 'ready']);
    } catch (Throwable) {
        return response()->json(['status' => 'unavailable'], 503);
    }
})->middleware('throttle:30,1');
Route::middleware(['auth', SchoolContext::class])->group(function () {
    Route::get('/dashboard', [SchoolController::class, 'dashboard']);
    Route::get('/settings', [SchoolController::class, 'settings']);
    Route::patch('/settings', [SchoolController::class, 'update']);
    Route::get('/students', [SchoolController::class, 'students']);
    Route::post('/students', [SchoolController::class, 'storeStudent']);
    Route::get('/organization', [SchoolController::class, 'organization']);
    Route::post('/organization/{kind}', [SchoolController::class, 'storeOrganization']);
});
