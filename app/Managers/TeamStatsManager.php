<?php

namespace App\Managers;

use App\Models\Team;
use App\Managers\WinPredictionManager;
use App\Pivot\GameMatchTeam;
use Illuminate\Support\Collection;

class TeamStatsManager
{
    const POINTS_PRICES = [
        'won' => 3,
        'lost' => 0,
        'draw' => 1,
    ];

    /**
     * @var array
     */
    protected $cached_stats = [];
    /**
     * @var Team
     */
    protected $team;
    /**
     * @var Collection
     */
    protected $matches;
    
    /**
     * Returns stats of the team or calculates them
     * 
     * @param Team $team
     * @param bool $reload
     * 
     * @return array
     */
    public function getStats(Team $team, bool $with_prediction = false, bool $reload = false): array
    {
        if($reload || count($this->cached_stats) < 1) {
            $this->team = $team;
            $this->cached_stats = $this->countStats($with_prediction);
        }
        
        return $this->cached_stats;
    }

    /**
     * Calculates stats of the team
     * 
     * @return array
     */
    private function countStats(bool $with_prediction = false): array
    {
        $this->matches = $this->getFinishedMatches();

        $stats = [
            'played' => $this->matches->count(),
            'won' => $this->getWonMatches()->count(),
            'draw' => $this->getDrawMatches()->count(),
            'lost' => 0,
            'goal_difference' => $this->getGoalDifference(),
            'left' => $this->team->matches->count() - $this->matches->count(),
        ];

        $stats['lost'] = $stats['played'] - $stats['won'] - $stats['draw'];

        $stats['points'] = $this->countPoints($stats);

        if($with_prediction && $stats['left'] > 0) {
            $stats['win_prediction_percent'] = WinPredictionManager::getWinPredictionByTeam($this->team->id);
        } elseif($with_prediction) {
            $stats['win_prediction_percent'] = 0;
        }

        return $stats;
    }

    /**
     * Filters all finished matches of the team
     * 
     * @return Collection
     */
    private function getFinishedMatches(): Collection
    {
        return $this->team->matches->where('finished', 1)->each(function($match) {
            $match->host = $match->getHost();
            $match->host_goals = $match->host->pivot->goals;
            $match->guest = $match->getGuest();
            $match->guest_goals = $match->guest->pivot->goals;
        });
    }

    /**
     * Filters team matches with win result
     * 
     * @return Collection
     */
    private function getWonMatches(): Collection
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

    /**
     * Filters team matches with draw result
     * 
     * @return Collection
     */
    private function getDrawMatches(): Collection
    {
        return $this->matches->filter(function($match) {
            if($match->host_goals === $match->guest_goals) {
                return true;
            }

            return false;
        });
    }

    /**
     * Calculates goal difference of the team
     * 
     * @return int
     */
    private function getGoalDifference(): int
    {
        $scored_goals = $this->matches->sum('pivot.goals');
        $missed_goals = (int) GameMatchTeam::whereIn('game_match_id', $this->matches->pluck('id')->toArray())
                                ->whereNot('team_id', $this->team->id)
                                ->sum('goals');

        return $scored_goals - $missed_goals;
    }

    /**
     * Counts team earned points by calculated stats
     * 
     * @param array $stats
     * 
     * @return int
     */
    private function countPoints(array $stats): int
    {
        $points = 0;
        
        foreach($stats as $stat_name => $stat_count) {
            if(isset(self::POINTS_PRICES[$stat_name])) {
                $points += $stat_count * self::POINTS_PRICES[$stat_name];
            }
        }

        return $points;
    }
}