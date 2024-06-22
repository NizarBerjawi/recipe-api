<?php

namespace App\Queries\Concerns;

trait HasIncludes
{
    /**
     * @var array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    protected $includes = [];

    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    abstract public function includes(): array;
}
