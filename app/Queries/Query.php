<?php

namespace App\Queries;

use App\Queries\Concerns\QueryFor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use ReflectionClass;

abstract class Query
{
    /**
     * An instance of the query builder.
     *
     * @var \App\Queries\QueryBuilder
     */
    protected $builder;

    /**
     * The query builder used to apply the filters.
     */
    public function builder(): QueryBuilder
    {
        $model = $this->subject();

        $builder = QueryBuilder::for($model::class)
            ->defaultSort($model->getKeyName())
            ->allowedSorts($this->sorts())
            ->allowedFields($this->fields())
            ->allowedIncludes($this->includes())
            ->allowedFilters($this->filters());


        $this->relations()->map(fn(string $relation) => $model->{$relation}());

        $fields = $builder->getRequestedFieldsForRelatedTable('recipeDetail');
        // die(json_encode($builder->getRequestedFieldsForRelatedTable('ingredients')));
        
        return $builder->with([
            'recipeDetail' => fn($query) => $query->select($builder->getRequestedFieldsForRelatedTable('recipeDetail'))->addSelect('recipe_uuid'),
            // 'ingredients' => fn($query) => $query->select($builder->getRequestedFieldsForRelatedTable('ingredients'))->addSelect('recipe_uuid'),
        ]);

    }

    /**
     * Return the "subject" for this query
     */
    public function subject(): Model
    {
        $reflectionClass = new ReflectionClass(static::class);

        $attributes = $reflectionClass->getAttributes(QueryFor::class);

        /** @var \ReflectionAttribute */
        $subject = Arr::first($attributes);

        /** @var string */
        $modelClass = Arr::first($subject->getArguments());

        return new $modelClass;
    }

    /**
     * The "fields" that can be selected to add to the query.
     *
     * @return array<int, string>
     */
    abstract public function fields(): array;

    /**
     * The attributes we can use to filter.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedFilter|string>
     */
    abstract public function filters(): array;

    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Illuminate\Support\Collection<int, \Spatie\QueryBuilder\AllowedInclude>>
     */
    abstract public function includes(): array;

    /**
     * The "relationships" that can be included in a response for this model.
     *
     * @return array<int, \Spatie\QueryBuilder\AllowedSort>
     */
    abstract public function sorts(): array;
}
