<?php

namespace App\Http\Resources\RecipeDetail;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RecipeDetailCollection extends ResourceCollection
{
    use ManagesJsonApiSpec;

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @return array<string, \Illuminate\Support\Collection<(int|string), mixed>>
     */
    public function with(Request $request): array
    {
        return $this->collectIncludes([
            'recipe', 'recipe.user',
        ]);
    }
}
