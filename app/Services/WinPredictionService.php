<?php

namespace App\Services;

use App\Models\GameWeek;
use App\Models\Team;
use App\Services\TeamStatsService;
use Illuminate\Support\Collection;

class WinPredictionService
{
    
    protected static $weeks_left;
    protected static $cached_predictions = null;

    /**
     * Gets win prediction percent of a certain team
     * 
     * @param int $team_id
     * 
     * @return int
     */
    public static function getWinPredictionByTeam(int $team_id): int
    {        
        return self::getWinPredictions()?->where('id', $team_id)?->first()?->calculated_prediction;
    }

    /**
     * Gets cached predictions or counts new
     * 
     * @return Collection
     */
    public static function getWinPredictions(): Collection
    {        
        if(! self::$cached_predictions) {

            self::$cached_predictions = collect(Team::inRandomOrder()->first());
            
            $weeks = GameWeek::all();

            self::$weeks_left = $weeks->reject(function($week_item) {
                return $week_item->isFinished();
            });

            $teams = self::getTeamsWithPoints();
            self::countPredictions($teams);

            self::$cached_predictions = $teams;
        }

        return self::$cached_predictions;
    }

    /**
     * Get all teams sorted by points
     * 
     * @return Collection
     */
    private static function getTeamsWithPoints(): Collection
    {
        $teams = Team::with('matches')->get();
        $teams = $teams->sortBy('points');

        return $teams;
    }

    /**
     * Counts teams chances to win the League basing
     * on their points and their history of matches
     * 
     * @param Collection $teams
     * 
     * @return Collection
     */
    private static function countPredictions(Collection &$teams): Collection
    {
        $max_points_possible = self::$weeks_left->count() * TeamStatsService::POINTS_PRICES['won'];
        $max_current_points = $teams->max('points');
        $possible_winners = [];

        foreach($teams as &$team) {
            $team->calculated_prediction = 0;

            // If team's points lag too big
            if(($team->points + $max_points_possible) < $max_current_points) {
                continue;
            }

            // Count team winning percent based on its history
            $win_proportion = 100 * $team->stats['won'] / ($team->stats['won'] + $team->stats['lost']);
            $possible_winners[$team->id] = $win_proportion;
        }

        $win_proportion_sum = array_sum($possible_winners);
        foreach($possible_winners as $pw_id => $pw_win_percent)
        {
            $sum_part = ($pw_win_percent / $win_proportion_sum) * 100;
            $teams->where('id', $pw_id)->first()->calculated_prediction = $sum_part;
        }

        return $teams;
    }
}
