<?php

use App\Http\Controllers\API\CrewController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::post('/{type}/{id}/logo/', [ImageController::class, 'setLogo']);

    Route::get('/user/{user}', [UserController::class, 'getUser']);
    Route::post('/user/{user}', [UserController::class, 'setUser']);

    Route::post('/crew/', [CrewController::class, 'createCrew']);
    Route::get('/crew/{crew}', [CrewController::class, 'getCrew']);
    Route::get('/crew/{crew}/users', [CrewController::class, 'getCrewUsers']);
    Route::get('/crew/{crew}/events', [CrewController::class, 'getCrewEvents']);
    Route::post('/crew/{crew}', [CrewController::class, 'updateCrew']);
    Route::delete('/crew/{crew}', [CrewController::class, 'deleteCrew']);
    Route::get('/crews', [CrewController::class, 'getListCrews']);
    Route::get('/other-crews', [CrewController::class, 'getListOtherCrews']);
    Route::get('/my-crews', [CrewController::class, 'getListMyCrews']);

    Route::post('/crew/{crew}/event/', [EventController::class, 'createEvent']);
    Route::get('/event/{event}', [EventController::class, 'getEvent']);
    Route::post('/event/{event}', [EventController::class, 'updateEvent']);
    Route::delete('/event/{event}', [EventController::class, 'deleteEvent']);
    Route::get('/event/{event}/choices', [EventController::class, 'getEventChoices']);
    Route::post('/event/{event}/choice', [EventController::class, 'setEventChoice']);
});
