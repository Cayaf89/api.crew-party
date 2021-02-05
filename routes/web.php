<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('guest')->group(function () {
    Route::get('/', [App\Http\Controllers\Views\WelcomeController::class, 'index']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Views\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-parameters', [App\Http\Controllers\Views\UserController::class, 'myParameters'])->name('my-parameters');

    Route::get('/crew/{crew}', [App\Http\Controllers\Views\CrewController::class, 'show'])->name('crew-show');
    Route::get('/crew/{crew}/event', [App\Http\Controllers\Views\CrewController::class, 'createEvent'])->name('crew-create-event');
    Route::get('/crew', [App\Http\Controllers\Views\CrewController::class, 'create'])->name('crew-create');
    Route::get('/crews', [App\Http\Controllers\Views\CrewController::class, 'index'])->name('crew-list');

    Route::get('/event/{event}', [App\Http\Controllers\Views\EventController::class, 'show'])->name('event-show');
    Route::get('/event', [App\Http\Controllers\Views\EventController::class, 'create'])->name('event-create');
    Route::get('/events', [App\Http\Controllers\Views\EventController::class, 'index'])->name('event-list');
});

