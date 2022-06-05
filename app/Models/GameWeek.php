<?php

namespace App\Models;

use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


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

    /**
     * Return true if all matches in the week are finished
     * 
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->matches->where('finished', 0)->count() === 0;
    }

    /**
     * Returns all unfinished matches of the week
     * 
     * @return Collection
     */
    public function getUnfinishedMatches(): Collection
    {
        return $this->matches->filter(function($match) {
            return ! $match->isFinished();
        });
    }

    /**
     * Returns all finished matches of the week
     * 
     * @return Collection
     */
    public function getFinishedMatches(): Collection
    {
        return $this->matches->filter(function($match) {
            return $match->isFinished();
        });
    }

    /**
     * Plays all unfinished matches of the week
     * 
     * @return int
     */
    public function playAllMatches(): int
    {
        $counter = 0;
        
        foreach($this->getUnfinishedMatches() as $match) {
            $match->finish();
            $counter++;
        }

        return $counter;
    }

    /**
     * Resets all matches of the week
     * 
     * @return int
     */
    public function resetAllMatches(): int
    {
        $counter = 0;
        
        foreach($this->getFinishedMatches() as $match) {
            $match->resetProgress();
            $counter++;
        }

        return $counter;
    }

    /**
     * Gets current game week
     * 
     * @return static
     */
    public static function getCurrentWeek(): static
    {
        $last_week = static::activeWeeks()->with('matches')->first();

        if(! $last_week) {
            $last_week = static::with('matches')->orderBy('week_order', 'desc')->first();
        }
        
        return $last_week;
    }

    /**
     * Plays all unfinished matches of all unfinished weeks
     * 
     * @return int
     */
    public static function playAllWeeks(): int
    {
        $counter = 0;
        
        foreach(static::activeWeeks()->get() as $week) {
            $counter += $week->playAllMatches();
        }

        return $counter;
    }

    /**
     * Resets all matches of all weeks
     * 
     * @return int
     */
    public static function resetAllWeeks(): int
    {
        $counter = 0;
        
        foreach(static::all() as $week) {
            $counter += $week->resetAllMatches();
        }

        return $counter;
    }

    public static function resetAllMatchesAndFixtures(): int
    {
        $counter = 0;
        
        foreach(static::all() as $week) {
            foreach($week->matches as $match) {
                $match->delete();
                $counter++;
            }

            $week->delete();
        }

        return $counter;
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
