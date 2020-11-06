<?php

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
    Route::post('/user/{user}', 'API\\UserController@setUser');
    Route::post('/user/logo/{user}', 'API\\UserController@setLogo');

    Route::post('/crew/', 'API\\CrewController@createCrew');
    Route::get('/crew/{crew}', 'API\\CrewController@getCrew');
    Route::post('/crew/{crew}', 'API\\CrewController@updateCrew');
    Route::post('/crew/logo/{crew}', 'API\\CrewController@setLogo');
    Route::delete('/crew/{crew}', 'API\\CrewController@deleteCrew');
    Route::get('/crews', 'API\\CrewController@getListCrews');
    Route::get('/other-crews', 'API\\CrewController@getListOtherCrews');
    Route::get('/my-crews', 'API\\CrewController@getListMyCrews');
});
