<?php

namespace App\Queries;

use App\Models\RecipeDetail;
use App\Models\Unit;
use App\Queries\Concerns\SubjectOf;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

#[SubjectOf(Unit::class)]
class UnitQuery extends Query
{
    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    public function includes(): array
    {
        return [
            AllowedInclude::relationship('ingredient'),
        ];
    }

    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedSort>
     */
    public function sorts(): array
    {
        return [
            AllowedSort::field('code'),
            AllowedSort::field('label'),
            AllowedSort::field('createdAt', 'created_at'),
            AllowedSort::field('updatedAt', 'updated_at'),
        ];
    }

    /**
     * The attributes we can use to filter.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedFilter|string>
     */
    public function filters(): array
    {
        return [
            AllowedFilter::exact('code'),
            AllowedFilter::partial('label'),
        ];
    }

    /**
     * A client MAY request that an endpoint return only specific fields
     * in the response on a per-type basis by including a fields[TYPE] query parameter.
     */
    public function fields(): array
    {
        return [
            'uuid',
            'code', 
            'label',
            'createdAt',
            'updatedAt',
        ];
    }
}
