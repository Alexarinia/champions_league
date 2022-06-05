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
    Route::post('/generate', [TeamController::class, 'generateTeams']);
    Route::get('/stats', [TeamController::class, 'getTeamsStatsList']);
    Route::get('/predictions', [TeamController::class, 'getTeamsPredictionsList']);
});

Route::group(['prefix' => 'weeks'], function () {
    Route::get('/', [GameWeekController::class, 'getGameWeeksList']);
    Route::get('/current', [GameWeekController::class, 'getCurrentGameWeek']);
    Route::post('/play', [GameWeekController::class, 'playGameWeek']);
    Route::post('/reset', [GameWeekController::class, 'resetAllMatches']);
    Route::post('/reset-all', [GameWeekController::class, 'resetAllMatchesAndFixtures']);
});

Route::group(['prefix' => 'matches'], function () {
    Route::post('/generate', [GameMatchController::class, 'generateFixtures']);
    Route::get('/count', [GameMatchController::class, 'getFixturesCount']);
});
