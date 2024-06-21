<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeDetail>
 */
class RecipeDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prep_time' => fake()->numberBetween(15, 120),
            'cook_time' => fake()->numberBetween(15, 120),
            'servings' => fake()->numberBetween(1, 20),
        ];
    }
}
