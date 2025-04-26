<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ManagesJsonApiSpec;
use App\Models\Api\Contracts\JsonApiResource;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\UserEmail
 */
class RelationshipResource extends JsonResource
{
    use ManagesJsonApiSpec;
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var JsonApiResource&Model|null */
        $relation = $this->resource;

        if (! $relation) {
            return ['data' => null];
        }

        if (! $relation instanceof Model && ! $relation instanceof JsonApiResource) {
            throw new Exception('Relations can only be collected for a resource of type: '.Model::class);
        }

        return [
            'data' => [
                'type' => $relation->getType(),
                'id' => $relation->getKey(),
            ],
        ];
    }
}
