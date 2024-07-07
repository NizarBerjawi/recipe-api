<?php

namespace App\Queries;

use App\Queries\Concerns\HasFields;
use App\Queries\Concerns\HasFilters;
use App\Queries\Concerns\HasIncludes;
use App\Queries\Concerns\HasSorts;
use App\Queries\Concerns\SubjectOf;
use App\Queries\QueryBuilder;
use Illuminate\Support\Arr;
use ReflectionClass;

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
    public function subject()
    {
        $reflectionClass = new ReflectionClass(static::class);

        $attributes = $reflectionClass->getAttributes(SubjectOf::class);

        /** @var \ReflectionAttribute */
        $subject = Arr::first($attributes);

        /** @var string */
        $modelClass = Arr::first($subject->getArguments());

        return $modelClass;
    }
}
