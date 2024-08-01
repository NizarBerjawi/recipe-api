<?php

namespace App\Http\Requests\Concerns;

class ForResource
{
    public string $resource;

    public function __construct(string $resource)
    {
        $this->resource = $resource;
    }
}
