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
        $matches = $this->getUnfinishedMatches();
        
        foreach($matches as $match) {
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

    public function getFinishedAttribute()
    {
        return $this->isFinished();
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
