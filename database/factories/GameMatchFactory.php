<?php

namespace Database\Factories;

use App\Models\GameWeek;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameMatch>
 */
class GameMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'team_1_id' => Team::factory()->create(),
            'team_2_id' => Team::factory()->create(),
            'team_1_goals' => $this->faker->numberBetween(0, 4),
            'team_2_goals' => $this->faker->numberBetween(0, 4),
            'game_week_id' => GameWeek::factory()->create(),
        ];
    }
}
