<?php

namespace App\Contracts\Repositories;

interface TeamRepositoryInterface
{
    public function all();

    public function regenerateTeams();

    public function withMatches();

    public function withPredictions();
}
