<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' ' . $this->faker->word(),
            'description' => $this->faker->optional()->sentence(),
            'repair' => $this->faker->boolean(30), 
            'work' => $this->faker->boolean(80),   
        ];
    }
}
