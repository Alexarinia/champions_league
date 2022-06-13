<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface GameWeekRepositoryInterface
{
    public function all(): Collection;

    public function getCurrentWeek(): ?object;

    public function getOrderedWeeksWithMatches(): Collection;

    public function playAllMatchesOfCurrentWeek(): int;

    public function playAllWeeks(): int;

    public function resetAllMatchesAndFixtures(): int;

    public function resetAllWeeks(): int;

}
