<?php

namespace App\Repositories;

use App\Models\Team;
use App\Contracts\Repositories\TeamRepositoryInterface;
use Illuminate\Support\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    /**
     * Gets all teams
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return Team::all();
    }

    /**
     * Truncates all old teams and generates new ones
     * 
     * @return int
     */
    public function regenerateTeams(): int
    {
        Team::truncate();
        Team::factory()->count(Team::TEAMS_COUNT)->create();

        return Team::TEAMS_COUNT;
    }

    /**
     * Gets all teams with matches
     * 
     * @return Collection
     */
    public function withMatches(): Collection
    {
        return Team::with('matches')->get();
    }

    /**
     * Gets all teams with win predictions
     * 
     * @return Collection
     */
    public function withPredictions(): Collection
    {
        $teams = Team::with('matches')->get();
        $teams = $teams->each->append('prediction');

        return $teams;
    }
}
