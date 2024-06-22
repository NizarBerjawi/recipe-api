<?php

namespace App\Queries;

use App\Queries\Concerns\HasFields;
use App\Queries\Concerns\HasFilters;
use App\Queries\Concerns\HasIncludes;
use App\Queries\Concerns\HasSorts;
use Spatie\QueryBuilder\QueryBuilder;

abstract class Query
{
    use HasFields, HasFilters, HasIncludes, HasSorts;

    /**
     * An instance of the query builder.
     *
     * @var \App\Queries\QueryBuilder
     */
    protected $builder;

    /**
     * Instantiate the Query.
     *
     * @return void
     */
    public function __construct()
    {
        $this->builder = QueryBuilder::for($this->subject());

        $this->includes = $this->includes();
        $this->sorts = $this->sorts();
        $this->filters = $this->filters();
        $this->fields = $this->fields();
    }

    /**
     * The query builder used to apply the filters.
     */
    public function builder(): QueryBuilder
    {
        $class = $this->subject();

        return $this->builder
            ->defaultSort((new $class)->getKeyName())
            ->allowedSorts($this->sorts)
            ->allowedFields($this->fields)
            ->allowedIncludes($this->includes)
            ->allowedFilters($this->filters);
    }

    /**
     * Return the "subject" for this query
     */
    abstract public function subject(): string;
}
