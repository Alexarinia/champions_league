<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TeamRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamCollection;

class TeamController extends Controller
{
    private $teams;

    public function __construct(TeamRepositoryInterface $team_repository)
    {
        $this->teams = $team_repository;
    }
    
    public function getTeamsList() {
        return new TeamCollection($this->teams->all());
    }

    public function getTeamsStatsList() {
        return new TeamCollection($this->teams->withMatches());
    }

    public function getTeamsPredictionsList() {
        return new TeamCollection($this->teams->withPredictions());
    }

    public function generateTeams()
    {
        return $this->teams->regenerateTeams();
    }
}
