<?php

namespace Database\Factories;

use App\Models\GameWeek;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameWeek>
 */
class GameWeekFactory extends Factory
{
    
    protected static $order = 1;


    /**
     * Create a new factory instance.
     *
     * @param  int|null  $count
     * @param  \Illuminate\Support\Collection|null  $states
     * @param  \Illuminate\Support\Collection|null  $has
     * @param  \Illuminate\Support\Collection|null  $for
     * @param  \Illuminate\Support\Collection|null  $afterMaking
     * @param  \Illuminate\Support\Collection|null  $afterCreating
     * @param  string|null  $connection
     * @return void
     */
    public function __construct($count = null,
                                ?Collection $states = null,
                                ?Collection $has = null,
                                ?Collection $for = null,
                                ?Collection $afterMaking = null,
                                ?Collection $afterCreating = null,
                                $connection = null)
    {
        
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);

        if(GameWeek::max('week_order')) {
            self::$order = GameWeek::max('week_order') + 1;
        } else {
            self::$order = 1;
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {       
        $week_order = self::$order++;
        
        return [
            'name' => "{$week_order} week",
            'week_order' => $week_order,
        ];
    }
}
