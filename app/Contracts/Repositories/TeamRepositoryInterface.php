<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    public function all(): Collection;

    public function regenerateTeams(): int;

    public function withMatches(): Collection;

    public function withPredictions(): Collection;
}
