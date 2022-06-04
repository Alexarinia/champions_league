<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameWeekCollection;
use App\Http\Resources\GameWeekResource;
use App\Models\GameWeek;
use Illuminate\Http\Request;

class GameWeekController extends Controller
{
    public function getGameWeeksList()
    {
        return new GameWeekCollection(GameWeek::orderBy('week_order')->get());
    }

    public function getCurrentGameWeek()
    {
        return new GameWeekResource(GameWeek::getCurrentWeek());
    }
}
