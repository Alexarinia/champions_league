<?php
 
namespace App\Pivot;
 
use Illuminate\Database\Eloquent\Relations\Pivot;
 
class GameMatchTeam extends Pivot
{
    protected $table = 'game_match_team';
}