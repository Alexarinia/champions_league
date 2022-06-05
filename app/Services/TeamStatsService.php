<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamStatsService
{
    protected $cached_stats = [];
    protected $team;
    protected $matches;
    
    public function getStats(Team $team, bool $reload = false)
    {
        if($reload || count($this->cached_stats) < 1) {
            $this->team = $team;
            logger($this->team->name);
            $this->cached_stats = $this->countStats();
        }
        
        return $this->cached_stats;
    }

    private function countStats()
    {
        $this->matches = $this->getFinishedMatches();

        $stats = [
            'played' => $this->matches->count(),
            'won' => $this->getWonMatches()->count(),
            'draw' => $this->getDrawMatches()->count(),
            'goal_difference' => $this->getGoalDifference(),
        ];

        $stats['lost'] = $stats['played'] - $stats['won'] - $stats['draw'];

        return $stats;
    }

    private function getFinishedMatches()
    {
        return $this->team->matches->where('finished', 1)->each(function($match) {
            $match->host = $match->getHost();
            $match->host_goals = $match->host->pivot->goals;
            $match->guest = $match->getGuest();
            $match->guest_goals = $match->guest->pivot->goals;
        });
    }

    private function getWonMatches()
    {
        return $this->matches->filter(function($match) {
            if($match->host_goals === $match->guest_goals) {
                return false;
            }

            if($match->host->id === $this->team->id) {
                return $match->host_goals > $match->guest_goals;
            } else {
                return $match->host_goals < $match->guest_goals;
            }
        });
    }

    private function getDrawMatches()
    {
        return $this->matches->filter(function($match) {
            if($match->host_goals === $match->guest_goals) {
                return true;
            }

            return false;
        });
    }

    private function getGoalDifference()
    {
        $scored_goals = $this->matches->sum('pivot.goals');
        $missed_goals = (int) DB::table('game_match_team')
                                ->whereIn('game_match_id', $this->matches->pluck('id')->toArray())
                                ->whereNot('team_id', $this->team->id)
                                ->sum('goals');

        return $scored_goals - $missed_goals;
    }

}