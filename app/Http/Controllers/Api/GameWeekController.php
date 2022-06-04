<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameWeekCollection;
use App\Models\GameWeek;
use Illuminate\Http\Request;

class GameWeekController extends Controller
{
    public function getGameWeeksList()
    {
        return new GameWeekCollection(GameWeek::all());
    }
}
