<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(3),
            'name' => fake()->text(20),
            'display_text' => Str::of(fake()->text(64))
                ->lcfirst()
                ->prepend('{{ $quantity }} {{ $name }} '),
        ];
    }
}
