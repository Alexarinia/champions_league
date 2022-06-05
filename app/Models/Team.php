<?php

namespace App\Models;

use App\Models\GameMatch;
use App\Services\TeamStatsService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use HasFactory;

    /*
    |-------------------------------------------------------------------------- 
    | GLOBAL VARIABLES 
    |-------------------------------------------------------------------------- 
    */

    protected $table = 'teams';

    protected $teamStatsService = null;
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->teamStatsService = new TeamStatsService;
    }

    public function getStats(): array
    {
        return $this->teamStatsService->getStats($this);
    }

    public function getStatsWithPrediction(): array
    {
        return $this->teamStatsService->getStats($this, true, true);
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
