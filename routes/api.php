<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/rooms', RoomController::class)->name('rooms');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
