<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeDetail;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UnitSeeder::class]);

        $users = User::factory()->count(50)->create();

        foreach ($users as $user) {
            $token = $user->createToken('access_token');
            Log::notice('TOKENS', [
                'userId' => $user->getKey(),
                'token' => $token->plainTextToken,
            ]);

            $recipes = Recipe::factory()
                ->count(fake()->numberBetween(0, 20))
                ->for($user)
                ->has(RecipeDetail::factory())
                ->has(
                    Direction::factory()->count(fake()->numberBetween(5, 15))
                )
                ->create();

            $ingredients = Ingredient::factory()
                ->count(fake()->numberBetween(1, 500))
                ->for($user)
                ->create();

            $recipes->each(function (Recipe $recipe) use ($ingredients) {
                $recipeIngredients = $ingredients->random(
                    fake()->numberBetween(1, $ingredients->count())
                );

                $recipeIngredients->each(
                    fn (Ingredient $ingredient) => $recipe->ingredients()->attach($ingredient, [
                        'unit_uuid' => Unit::inRandomOrder()->limit(1)->first()->getKey(),
                        'quantity' => fake()->randomFloat(),
                    ])
                );
            });
        }
    }
}
