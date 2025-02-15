<?php

namespace App\Queries;

use App\Queries\Concerns\AddsFieldsToQuery;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class QueryBuilder extends SpatieQueryBuilder
{
    use AddsFieldsToQuery;
}
