<?php

namespace App\Http\Resources\Ingredient;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
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
                'name' => $this->resource->name,
                'quantity' => $this->resource->quantity,
                'displayText' => $this->resource->display_text,
                'createdAt' => $this->resource->created_at,
                'updatedAt' => $this->resource->updated_at,
            ],
            'relationships' => $this->collectRelationships(['recipe', 'unit']),
            'links' => [
                'self' => route('ingredient.show', $this->resource->getKey())
            ]
        ];
    }
}
