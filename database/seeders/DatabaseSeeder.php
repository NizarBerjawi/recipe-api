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
        $maxUsers = env(key: 'MAX_USERS', default: fake()->numberBetween(5, int2: 50));
        $maxRecipes = env(key: 'MAX_RECIPES', default: fake()->numberBetween(0, int2: 20));
        $maxDirections = env(key: 'MAX_DIRECTIONS', default: fake()->numberBetween(5, 15));
        $maxIngredients = env(key: 'MAX_INGREDIENTS', default: fake()->numberBetween(1, 500));

        $this->call(class: [UnitSeeder::class]);

        $users = User::factory()->count($maxUsers)->create();

        foreach ($users as $user) {
            $token = $user->createToken('access_token');
            
            Log::notice('TOKENS', [
                'userId' => $user->getKey(),
                'token' => $token->plainTextToken,
            ]);

            $recipes = Recipe::factory()
                ->count($maxRecipes)
                ->for($user)
                ->has(RecipeDetail::factory())
                ->has(Direction::factory()->count($maxDirections))
                ->create();

            $recipes->each(function (Recipe $recipe) use ($maxIngredients) {
                $unit = Unit::inRandomOrder()->limit(1)->first();

                Ingredient::factory()
                    ->count($maxIngredients)
                    ->for($recipe)
                    ->for($unit);                    
            });
        }
    }
}
