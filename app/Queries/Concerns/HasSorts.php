<?php

namespace App\Queries\Concerns;

trait HasSorts
{
    /**
     * @var array<int, \Spatie\QueryBuilder\AllowedSort>
     */
    protected $sorts = [];

    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedSort>
     */
    abstract public function sorts(): array;
}
