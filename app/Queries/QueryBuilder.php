<?php

namespace App\Queries;

use App\Queries\Concerns\AddsFieldsToQuery;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

/**
 * @mixin EloquentBuilder
 */
class QueryBuilder extends SpatieQueryBuilder
{
    use AddsFieldsToQuery;
}