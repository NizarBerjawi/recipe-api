<?php

namespace App\Queries\Concerns;

trait HasFilters
{
    /**
     * @var array<int, \Spatie\QueryBuilder\AllowedFilter|string>
     */
    protected $filters = [];

    /**
     * The attributes we can use to filter.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedFilter|string>
     */
    abstract public function filters(): array;
}
