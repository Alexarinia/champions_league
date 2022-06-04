<?php

use App\Http\Controllers\Api\GameMatchController;
use App\Http\Controllers\Api\GameWeekController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
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

Route::group(['prefix' => 'teams'], function () {
    Route::get('/', [TeamController::class, 'getTeamsList']);
});

Route::group(['prefix' => 'weeks'], function () {
    Route::get('/', [GameWeekController::class, 'getGameWeeksList']);
});

Route::group(['prefix' => 'matches'], function () {
    Route::post('/generate', [GameMatchController::class, 'generateFixtures']);
});
