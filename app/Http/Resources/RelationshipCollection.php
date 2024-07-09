<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin \App\Models\UserEmail
 */
class RelationshipCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (JsonResource $item) => [
                'type' => $item->resource->getType(),
                'id' => $item->resource->getKey(),
            ]),
        ];
    }
}
