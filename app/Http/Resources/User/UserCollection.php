<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            'user',
            'recipeDetail',
            'directions',
            'ingredients',
            'ingredients.unit'
        ]);
    }
}
