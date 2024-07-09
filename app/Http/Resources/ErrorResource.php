<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin \App\Models\UserEmail
 */
class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => Arr::get($this->resource, 'status'),
            'detail' => Arr::get($this->resource, 'detail'),
        ];
    }
}
