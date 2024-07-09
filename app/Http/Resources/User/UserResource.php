<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
                'email' => $this->resource->email,
                'createdAt' => $this->resource->created_at,
                'updatedAt' => $this->resource->updated_at,
            ],
            'relationships' => $this->collectRelationships([
                'recipes', 
            ]),
        ];
    }
}
