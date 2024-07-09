<?php

namespace App\Http\Resources;

use App\Models\Api\ApiModel;
use App\Models\Api\ApiUser;
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
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ApiModel|ApiUser|JsonApiResource|null */
        $model = $this->resource;

        if (! $model) {
            return [
                'data' => null,
            ];
        }

        if (! $model instanceof Model) {
            throw new Exception('Relations can only be collected for a resource of type: '.Model::class);
        }

        if (! $model instanceof JsonApiResource) {
            throw new Exception('Relations can only be collected for a resource of type: '.JsonApiResource::class);
        }

        return [
            'data' => [
                'type' => $this->resource->getType(),
                'id' => $this->resource->getKey(),
            ],
        ];
    }
}
