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

Route::get('/', [App\Http\Controllers\Views\WelcomeController::class, 'index']);

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Views\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/crew/{crew}', [App\Http\Controllers\Views\CrewController::class, 'show'])->name('dashboard');
});

