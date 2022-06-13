<?php

namespace App\Repositories;

use App\Managers\FixtureGenerateManager;
use App\Models\GameMatch;
use App\Contracts\Repositories\GameMatchRepositoryInterface;
use Illuminate\Support\Collection;

class GameMatchRepository implements GameMatchRepositoryInterface
{
    /**
     * Gets all matches
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return GameMatch::all();
    }

    /**
     * Counts matches in system
     * 
     * @return int
     */
    public function count(): int
    {
        return GameMatch::count();
    }

    /**
     * Generates all fixtures for game weeks
     * 
     * @return int
     */
    public static function generateFixtures(): int
    {
        return FixtureGenerateManager::generateFixtures();
    }
}
