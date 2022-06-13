<?php

namespace App\Repositories;

use App\Contracts\Repositories\GameWeekRepositoryInterface;
use App\Models\GameMatch;
use App\Models\GameWeek;
use App\Pivot\GameMatchTeam;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GameWeekRepository implements GameWeekRepositoryInterface
{
    /**
     * Gets all weeks
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return GameWeek::all();
    }

    /**
     * Gets weeks with matches ordered by week order
     * 
     * @return Collection
     */
    public function getOrderedWeeksWithMatches(): Collection
    {
        return GameWeek::with('matches')->orderBy('week_order')->get();
    }

    /**
     * Gets current game week
     * 
     * @return GameWeek|null
     */
    public function getCurrentWeek(): ?GameWeek
    {
        $last_week = GameWeek::activeWeeks()->with('matches')->first();

        if(! $last_week) {
            $last_week = $this->getOrderedWeeksWithMatches()->first();
            $last_week->append('finished');
        }
        
        return $last_week;
    }

    /**
     * Plays all unfinished matches of the week
     * 
     * @return int
     */
    public function playAllMatchesOfCurrentWeek(): int
    {
        $counter = 0;

        $week = $this->getCurrentWeek();
        $matches = $week->getUnfinishedMatches();
        
        foreach($matches as $match) {
            $match->finish();
            $counter++;
        }

        return $counter;
    }

    /**
     * Plays all unfinished matches of all unfinished weeks
     * 
     * @return int
     */
    public function playAllWeeks(): int
    {
        $counter = 0;
        
        foreach(GameWeek::activeWeeks()->get() as $week) {
            $counter += $week->playAllMatches();
        }

        return $counter;
    }

    /**
     * Resets all matches of all weeks
     * 
     * @return int
     */
    public function resetAllWeeks(): int
    {
        $counter = 0;
        $weeks = GameWeek::all();
        
        foreach($weeks as $week) {
            $counter += $week->resetAllMatches();
        }

        return $counter;
    }

    /**
     * Delete all matches, weeks and fixtures
     * 
     * @return int
     */
    public function resetAllMatchesAndFixtures(): int
    {
        $counter = GameMatch::count();
        GameMatchTeam::truncate();
        GameMatch::truncate();
        GameWeek::truncate();

        return $counter;
    }

}
