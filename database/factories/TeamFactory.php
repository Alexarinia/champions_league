<?php

namespace Database\Factories;

use App\Exceptions\FactoryException;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected static $used_names = [];
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

        if(Team::count() > 0) {
            $team_names = Team::select('name')->get();
            self::$used_names = $team_names->pluck('name')->toArray();
        }
    }

    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $unused_names = array_diff(config('insider.teams'), self::$used_names);

        if(count($unused_names) === 0) {
            throw new FactoryException('There are no more unique team names');
        }

        $name = $this->faker->randomElement($unused_names);
        self::$used_names[] = $name;
        
        return [
            'name' => $name,
            'team_power' => $this->faker->numberBetween(10, 100),
        ];
    }
}
