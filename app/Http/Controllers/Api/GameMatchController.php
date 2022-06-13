<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\GameMatchRepositoryInterface;
use App\Http\Controllers\Controller;

class GameMatchController extends Controller
{
    private $matches;

    public function __construct(GameMatchRepositoryInterface $match_repository)
    {
        $this->matches = $match_repository;
    }

    public function generateFixtures()
    {
        return $this->matches->generateFixtures();
    }

    public function getFixturesCount()
    {
        return $this->matches->count();
    }
}
