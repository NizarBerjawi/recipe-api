<?php

namespace App\Queries;

use App\Queries\Concerns\SubjectOf;
use App\Models\Recipe;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

#[SubjectOf(Recipe::class)]
class RecipeQuery extends Query
{
    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    public function includes(): array
    {
        return [
            AllowedInclude::relationship('user'),
            AllowedInclude::relationship('recipeDetail'),
            AllowedInclude::relationship('directions'),
            AllowedInclude::relationship('ingredients'),
            AllowedInclude::relationship('ingredients.unit'),
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
            AllowedSort::field('name'),
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
            AllowedFilter::exact('name'),
            AllowedFilter::partial('user.name'),
            AllowedFilter::exact('user.email'),
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
            'name',
            'description',
            'createdAt',
            'updatedAt',

            'ingredients.name',
            'ingredients.recipeUuid',
            'ingredients.displayText',

            'ingredients.unit.code',
            'ingredients.unit.label',

            'recipeDetail.prepTime',
            'recipeDetail.cookTime',
            'recipeDetail.servings',
            'recipeDetail.recipeUuid',
        ];
    }
}
