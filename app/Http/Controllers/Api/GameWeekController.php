<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameWeek\GameWeekPlayAllRequest;
use App\Http\Resources\GameWeekCollection;
use App\Http\Resources\GameWeekResource;
use App\Models\GameWeek;

class GameWeekController extends Controller
{
    public function getGameWeeksList()
    {
        return new GameWeekCollection(GameWeek::with('matches')->orderBy('week_order')->get());
    }

    public function getCurrentGameWeek()
    {
        $current_week = GameWeek::getCurrentWeek();
        
        if($current_week) {
            return new GameWeekResource($current_week);
        }
        
        return null;
    }

    public function playGameWeek(GameWeekPlayAllRequest $request)
    {
        if($request->has('all') && $request->all == true) {
            return GameWeek::playAllWeeks();
        } else {
            return GameWeek::getCurrentWeek()->playAllMatches();
        }
    }

    public function resetAllMatches()
    {
        return GameWeek::resetAllWeeks();
    }

    public function resetAllMatchesAndFixtures()
    {
        return GameWeek::resetAllMatchesAndFixtures();
    }
}
