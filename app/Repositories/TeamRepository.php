<?php

namespace App\Repositories;

use App\Models\Team;
use App\Contracts\Repositories\TeamRepositoryInterface;

class TeamRepository implements TeamRepositoryInterface
{
    public function all()
    {
        return Team::all();
    }

    public function withMatches()
    {
        return Team::with('matches')->get();
    }

    public function withPredictions()
    {
        $teams = Team::with('matches')->get();
        $teams = $teams->each->append('prediction');

        return $teams;
    }
}
