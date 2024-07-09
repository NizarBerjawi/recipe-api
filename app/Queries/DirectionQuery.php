<?php

namespace App\Queries;

use App\Models\Direction;
use App\Queries\Concerns\SubjectOf;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

#[SubjectOf(Direction::class)]
class DirectionQuery extends Query
{
    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    public function includes(): array
    {
        return [
            AllowedInclude::relationship('recipe'),
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
            AllowedSort::field('order'),
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
            AllowedFilter::exact('order'),
            AllowedFilter::partial('direction'),
            AllowedFilter::exact('recipe_uuid'),
        ];
    }

    /**
     * A client MAY request that an endpoint return only specific fields
     * in the response on a per-type basis by including a fields[TYPE] query parameter.
     *
     * @OA\Parameter(
     *     parameter="countryFields.countries.name",
     *     name="fields[country]",
     *     in="query",
     *     required=false,
     *
     *     @OA\Schema(
     *         type="array",
     *
     *         @OA\Items(
     *              type="string",
     *              enum={"name", "timezone", "environmentCode", "createdAt", "updatedAt"}
     *         )
     *     )
     * )
     */
    public function fields(): array
    {
        return [
            'uuid',
            'direction',
            'order',
            'recipeUuid',
            'createdAt',
            'updatedAt',
        ];
    }
}
