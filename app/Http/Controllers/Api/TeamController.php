<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamCollection;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getTeamsList() {
        return new TeamCollection(Team::all());
    }

    public function getTeamsStatsList() {
        return new TeamCollection(Team::with('matches')->get());
    }

    public function getTeamsPredictionsList() {
        $teams = Team::with('matches')->get();
        $teams = $teams->each->append('prediction');

        return new TeamCollection($teams);
    }

    public function generateTeams()
    {
        return Team::regenerateTeams();
    }
}
