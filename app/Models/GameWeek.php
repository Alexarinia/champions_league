<?php

namespace App\Models;

use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameWeek extends Model
{
    use HasFactory;

    /*
    |-------------------------------------------------------------------------- 
    | GLOBAL VARIABLES 
    |-------------------------------------------------------------------------- 
    */

    protected $table = 'game_weeks';
    protected $fillable = [
        'name',
        'week_order',
    ];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function isFinished(): bool
    {
        return $this->matches->where('finished', 0)->count() > 0;
    }

    /**
     * Gets current game week
     * 
     * @return static
     */
    public static function getCurrentWeek(): static
    {
        return static::activeWeeks()->first();
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function matches()
    {
        return $this->hasMany(GameMatch::class);
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Filters only weeks with unfinished matches
     * 
     * @param Builder $query
     * 
     * @return [type]
     */
    public function scopeActiveWeeks(Builder $query)
    {
        return $query->whereHas('matches', function(Builder $match_query) {
            return $match_query->unfinished();
        })->orderBy('week_order');
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
