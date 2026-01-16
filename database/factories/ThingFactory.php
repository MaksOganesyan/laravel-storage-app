<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'wrnt' => $this->faker->dateTimeBetween('now', '+2 years'),
            'amount' => $this->faker->numberBetween(1, 10),
            // НЕ указываем master_id и place_id — они будут передаваться вручную
        ];
    }
}
