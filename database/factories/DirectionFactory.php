<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direction>
 */
class DirectionFactory extends Factory
{
    /**
     * The current order being used by the factory.
     */
    protected static ?int $order = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $definition = [
            'direction' => fake()->text(),
            'order' => static::$order
        ];

        ++static::$order;

        if ($this->count < static::$order) {
            static::$order = 1;
        };

        return $definition;
    }
}
