<?php

namespace App\Http\Resources\RecipeDetail;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeDetailResource extends JsonResource
{
    use ManagesJsonApiSpec;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->resource->getType(),
            'id' => $this->resource->getKey(),
            'attributes' => [
                'prepTime' => $this->resource->prep_time,
                'cookTime' => $this->resource->cook_time,
                'servings' => $this->resource->servings,
                'createdAt' => $this->resource->created_at,
                'updatedAt' => $this->resource->updated_at,
            ],
            'relationships' => $this->collectRelationships(['recipe']),
        ];
    }
}
