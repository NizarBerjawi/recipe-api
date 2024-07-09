<?php

namespace App\Models\Api\Concerns;

/**
 * @method string getIdentifier()
 */
trait HasIdentifier
{
    /**
     * Returns the unique identifier of the model
     */
    public function getIdentifier(): string
    {
        return $this->getType().'-'.$this->getKey();
    }
}
