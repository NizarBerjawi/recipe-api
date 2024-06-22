<?php

namespace App\Queries\Concerns;

trait HasFields
{
    /**
     * @var array<int, string>
     */
    protected $fields = [];

    /**
     * The "fields" that can be selected to add to the query.
     *
     * @return array<int, string>
     */
    abstract public function fields(): array;
}
