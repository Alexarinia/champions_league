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
}
