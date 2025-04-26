<?php

namespace App\Http\Resources;

use App\Models\Api\Contracts\JsonApiResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinksResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'links';
    
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, private ?string $relationName = null)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = config('app.url').'/'.$this->resource->getType() . '/' . $this->resource->getKey();

        return [
            'links' => [
                'self' => "$url/relationships/{$this->relationName}",
                'related' => "$url/{$this->relationName}",
            ]
        ];
    }
}
