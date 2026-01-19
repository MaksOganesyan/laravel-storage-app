<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->optional()->sentence(),
            'wrnt' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'amount' => $this->faker->numberBetween(1, 50),
            'master_id' => User::factory(),
            'place_id' => Place::factory(),
        ];
    }
}
