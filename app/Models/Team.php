<?php

namespace App\Models;

use App\Managers\TeamStatsManager;
use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /*
    |-------------------------------------------------------------------------- 
    | GLOBAL VARIABLES 
    |-------------------------------------------------------------------------- 
    */
    
    const TEAMS_COUNT = 8;

    protected $table = 'teams';

    protected $teamStatsManager = null;
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->teamStatsManager = new TeamStatsManager;
    }

    public function getStats(): array
    {
        return $this->teamStatsManager->getStats($this);
    }

    public function getStatsWithPrediction(): array
    {
        return $this->teamStatsManager->getStats($this, true, true);
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function matches()
    {
        return $this->belongsToMany(GameMatch::class, 'game_match_team', 'team_id', 'game_match_id')->withPivot(['host', 'goals']);
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

    public function getStatsAttribute()
    {
        return $this->getStats();
    }

    public function getStatsWithPredictionAttribute()
    {
        return $this->getStatsWithPrediction();
    }

    public function getPointsAttribute()
    {
        return $this->stats['points'] ?? null;
    }

    public function getPredictionAttribute()
    {
        return $this->statsWithPrediction['win_prediction_percent'] ?? 0;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
