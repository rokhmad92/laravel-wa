<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerController;
use App\Http\Controllers\zoomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(registerController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/', 'login');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerAksi')->name('register.Aksi');
    Route::get('/otp/{nomer}', 'verifyOTP')->name('verifyOTP');
    Route::post('/otp/{nomer}', 'verifyOTPAksi');
    // resend
    Route::get('/otp/verify/{nomer}', 'resendOTP')->middleware('throttle:otp');
});

Route::controller(zoomController::class)->group(function () {
    Route::get('zoom', 'index');
});
