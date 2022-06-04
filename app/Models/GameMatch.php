<?php

namespace App\Models;

use App\Models\GameWeek;
use App\Models\Team;
use App\Services\FinishMatchService;
use App\Services\FixtureGenerateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    use HasFactory;

    /*
    |-------------------------------------------------------------------------- 
    | GLOBAL VARIABLES 
    |-------------------------------------------------------------------------- 
    */

    protected $table = 'game_matches';

    protected $finishMatchService = null;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->finishMatchService = new FinishMatchService;
    }

    /**
     * Gets host team
     * 
     * @return Team
     */
    public function getHost(): Team
    {
        return $this->teams->where(function ($team) {
            return $team->pivot->host === 1;
        })->first();
    }

    /**
     * Gets guest team
     * 
     * @return Team
     */
    public function getGuest(): Team
    {
        return $this->teams->where(function ($team) {
            return $team->pivot->host === 0;
        })->first();
    }

    /**
     * Returns true if match is finished
     * 
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished === 1;
    }

    /**
     * Counts goals and winner, finish the match
     * 
     * @return array
     */
    public function finish(): array
    {
        return $this->finishMatchService->finishMatch($this);
    }

    /**
     * Reset match progress
     * 
     * @return void
     */
    public function resetProgress(): void
    {
        $this->finished = 0;
        $this->save();

        $this->teams()->updateExistingPivot($this->getHost()->id, [
            'goals' => null,
        ]);
        $this->teams()->updateExistingPivot($this->getGuest()->id, [
            'goals' => null,
        ]);
    }
    
    /**
     * Generates all fixtures for game weeks
     * 
     * @return int
     */
    public static function generateFixtures(): int
    {
        return FixtureGenerateService::generateFixtures();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'game_match_team', 'game_match_id', 'team_id')->withPivot(['host', 'goals']);
    }

    public function week()
    {
        return $this->belongsTo(GameWeek::class, 'game_week_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFinished(Builder $query)
    {
        return $query->where('finished', 1);
    }

    public function scopeUnfinished(Builder $query)
    {
        return $query->where('finished', 0);
    }
    
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
