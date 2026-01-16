<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'repair' => $this->faker->boolean(30),
            'work' => $this->faker->boolean(70),
            // НЕ указываем owner_id — передадим вручную
        ];
    }
}
