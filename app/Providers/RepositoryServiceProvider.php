<?php

namespace App\Providers;

use App\Contracts\Repositories\GameMatchRepositoryInterface;
use App\Contracts\Repositories\TeamRepositoryInterface;
use App\Repositories\GameMatchRepository;
use App\Repositories\TeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GameMatchRepositoryInterface::class, GameMatchRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
