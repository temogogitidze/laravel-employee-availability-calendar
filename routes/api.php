<?php

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\PublicHolidayController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('public-holidays', [PublicHolidayController::class, 'index'])->name('public_holidays.get');
Route::post('calendar-events', [CalendarEventController::class, 'post'])->name('calendar_event.post');

// get all reserved days for user
//Route::get('get-user-calendar', [PublicHolidayController::class, 'index'])->name('public_holidays.get');
