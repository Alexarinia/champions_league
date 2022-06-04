<?php

namespace App\Models;

use App\Models\GameWeek;
use App\Models\Team;
use App\Services\FixtureGenerateService;
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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getHost()
    {
        return $this->teams->where(function ($team) {
            return $team->pivot->host === 1;
        })->first();
    }

    public function getGuest()
    {
        return $this->teams->where(function ($team) {
            return $team->pivot->host === 0;
        })->first();
    }

    public function isFinished(): bool
    {
        return $this->finished === 1;
    }
    
    public static function generateFixtures()
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
        return $this->belongsToMany(Team::class, 'game_match_team', 'team_id', 'game_match_id')->withPivot(['host', 'goals']);
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
