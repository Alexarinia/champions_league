<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface GameMatchRepositoryInterface
{
    public function all(): Collection;

    public function count(): int;

    public static function generateFixtures(): int;
}
