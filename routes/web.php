<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerController;

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
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerAksi')->name('register.Aksi');
    Route::get('/otp/{nomer}', 'verifyOTP')->name('verifyOTP');
});
