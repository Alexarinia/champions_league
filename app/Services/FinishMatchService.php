<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Team;

class FinishMatchService
{
    protected $goals_probabilities = [
        0 => 25,
        1 => 25,
        2 => 20,
        3 => 10,
        4 => 10,
        5 => 5,
        6 => 3,
        7 => 2,
    ];

    public function finishMatch(GameMatch $match)
    {
        $host = $match->getHost();
        $guest = $match->getGuest();

        $weights = $this->countWeights($host, $guest);
        $winner = $this->calculateMatchWinner($weights);
        $weights['winner'] = $winner;
        $weights['goals'] = $this->calculateGoals($winner);

        return $weights;
    }

    /**
     * Counts teams chances to win and draw possibility
     * 
     * @param Team $host
     * @param Team $guest
     * 
     * @return array
     */
    private function countWeights(Team $host, Team $guest): array
    {
        $host_chance = 100 * ($host->team_power / ($host->team_power + $guest->team_power));
        $guest_chance = 100 - $host_chance;

        $diff = abs($host->team_power - $guest->team_power);
        $draw_chance = (100 - $diff) / 2;

        $weights = [
            'host_power' => $host->team_power,
            'host' => $host_chance - ($draw_chance / 2),
            'draw' => $draw_chance,
            'guest' => $guest_chance - ($draw_chance / 2),
            'guest_power' => $guest->team_power,
        ];

        return $weights;
    }

    /**
     * Determines match winner basing on chances
     * 
     * @param array $weights
     * 
     * @return string
     */
    private function calculateMatchWinner(array $weights): string
    {
        $host_minimum = 100 - $weights['host'];
        $guest_maximum = $weights['guest'];

        $random_number = mt_rand(0, 100);

        if($random_number > $host_minimum) {
            $winner = 'host';
        } elseif($random_number < $guest_maximum) {
            $winner = 'guest';
        } else {
            $winner = 'draw';
        }

        return $winner;
    }

    /**
     * Generate match goals depending on winner or draw
     * 
     * @param string $winner
     * 
     * @return array
     */
    private function calculateGoals(string $winner): array
    {
        $winner_goals = $this->getRandomGoals();

        if($winner === 'draw') {
            $loser_goals = $winner_goals;
        } else {
            $loser_goals = $this->getRandomGoals($winner_goals);
        }

        $goals = [
            'host' => (($winner === 'host' || $winner === 'draw') ? $winner_goals : $loser_goals),
            'guest' => ($winner === 'guest' ? $winner_goals : $loser_goals),
        ];

        return $goals;
    }

    /**
     * Gets random count of goals with possible maximum restriction
     * 
     * @param int|null $max
     * 
     * @return int
     */
    private function getRandomGoals(?int $max = null): int
    {
        $goals_array = [];

        foreach($this->goals_probabilities as $count => $chance) {
            if(! is_null($max) && $count === $max) {
                break;
            }
            $goals_array = array_pad($goals_array, (count($goals_array) + $chance), $count);
        }

        shuffle($goals_array);

        return array_pop($goals_array);
    }
}
