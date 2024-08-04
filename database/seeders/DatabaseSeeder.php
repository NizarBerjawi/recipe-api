<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeDetail;
use App\Models\Unit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(10)->create();

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
                    Direction::factory()
                        ->count(fake()->numberBetween(5, 15))
                )
                ->create();

            $units = Unit::factory()
                ->count(fake()->numberBetween(1, 20))
                ->create();

            $recipes->each(function (Recipe $recipe) use ($units) {
                // Prepare the ingredients for each Recipe
                $ingredients = Ingredient::factory()
                    ->count(fake()->numberBetween(0, 15))
                    ->make();

                // Give every ingredients a unit and link to recipe
                $ingredients->each(
                    function (Ingredient $ingredient) use ($recipe, $units) {
                        $ingredient->unit()->associate($units->random());
                        $ingredient->recipe()->associate($recipe);

                        $ingredient->save();
                    }
                );
            });
        }
    }
}
