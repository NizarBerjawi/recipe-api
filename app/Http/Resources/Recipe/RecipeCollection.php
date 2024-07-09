<?php

namespace App\Http\Resources\Recipe;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use App\Queries\RecipeQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RecipeCollection extends ResourceCollection
{
    use ManagesJsonApiSpec;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->all();
    }

        /**
     * Get any additional data that should be returned with the resource array.
     *
     * @return array<string, \Illuminate\Support\Collection<(int|string), mixed>>
     */
    public function with(Request $request): array
    {

        return $this->collectIncludes([
            'user', 
            'recipeDetail',
            'directions',
            'ingredients',
            'ingredients.unit'
        ]);
    }
}
