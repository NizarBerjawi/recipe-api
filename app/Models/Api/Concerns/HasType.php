<?php

namespace App\Models\Api\Concerns;

use Illuminate\Support\Str;

/**
 * @method string getType()
 */
trait HasType
{
    /**
     * Returns the object "type" which is required by the JSON
     * API specification in responses
     */
    public function getType(): string
    {
        return Str::afterLast(static::class, '\\');
    }
}
