<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\PropertyPhotoController;
use App\Http\Controllers\User\BookingController;
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

Route::middleware('auth:sanctum')->group(function() {

    Route::prefix('user')->group(function () {
        Route::resource('bookings',BookingController::class);
        });

    Route::prefix('owner')->group(function () {
        Route::get('properties',[PropertyController::class, 'index']);
        Route::post('properties',[PropertyController::class, 'store']);
        Route::post('properties/{property}/photos',[PropertyPhotoController::class, 'store']);
        Route::post('properties/{property}/photos/{photo}/reorder/{newPosition}',
        [PropertyPhotoController::class,'reorder']);
        });
    });


Route::post('auth/register', App\Http\Controllers\Auth\RegisterController::class);
Route::get('search',
    \App\Http\Controllers\Public\PropertySearchController::class);

Route::get('properties/{property}',
    \App\Http\Controllers\Public\PropertyController::class);

Route::get('apartments/{apartment}', Public\ApartmentController::class);