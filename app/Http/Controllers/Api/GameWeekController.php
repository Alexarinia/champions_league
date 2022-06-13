<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\GameWeekRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameWeek\GameWeekPlayAllRequest;
use App\Http\Resources\GameWeekCollection;
use App\Http\Resources\GameWeekResource;

class GameWeekController extends Controller
{
    private $weeks;

    public function __construct(GameWeekRepositoryInterface $week_repository)
    {
        $this->weeks = $week_repository;
    }

    public function getGameWeeksList()
    {
        return new GameWeekCollection($this->weeks->getOrderedWeeksWithMatches());
    }

    public function getCurrentGameWeek()
    {
        $current_week = $this->weeks->getCurrentWeek();
        
        if($current_week) {
            return new GameWeekResource($current_week);
        }
        
        return null;
    }

    public function playGameWeek(GameWeekPlayAllRequest $request)
    {
        if($request->has('all') && $request->all == true) {
            return $this->weeks->playAllWeeks();
        } else {
            return $this->weeks->playAllMatchesOfCurrentWeek();
        }
    }

    public function resetAllMatches()
    {
        return $this->weeks->resetAllWeeks();
    }

    public function resetAllMatchesAndFixtures()
    {
        return $this->weeks->resetAllMatchesAndFixtures();
    }
}
