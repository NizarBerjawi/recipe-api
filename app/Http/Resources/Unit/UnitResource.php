<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
                'code' => $this->resource->code,
                'label' => $this->resource->label,
                'createdAt' => $this->resource->created_at,
                'updatedAt' => $this->resource->updated_at,
            ],
            'links' => [
                'self' => route('units.show', $this->resource->getKey())
            ]
        ];
    }
}
