<?php

namespace App\Models\Api\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

interface JsonApiResource
{
    /**
     * Returns the object "type" which is required by the JSON
     * API specification in responses.
     *
     * @see \App\Models\Api\Concerns\HasType
     */
    public function getType(): string;

    /**
     * Returns the unique identifier of the model required to
     * remove duplicate resources from JSON API specification
     * responses.
     *
     * @see \App\Models\Api\Concerns\HasIdentifier
     */
    public function getIdentifier(): string;

    /**
     * Return the API Resource object responsible for representing
     * the model in the JSON API response.
     *
     * @see \App\Models\Api\Concerns\HasResource
     */
    public function getResource(): JsonResource;
}
