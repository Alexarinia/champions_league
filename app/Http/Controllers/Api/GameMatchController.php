<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;

class GameMatchController extends Controller
{

    public function generateFixtures()
    {
        return GameMatch::generateFixtures();
    }

    public function getFixturesCount()
    {
        return GameMatch::count();
    }
}
