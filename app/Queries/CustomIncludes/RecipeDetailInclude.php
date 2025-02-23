<?php 

namespace App\Queries\CustomIncludes;

use App\Queries\RecipeQuery;
use Log;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;

class RecipeDetailInclude implements IncludeInterface
{    
    /**
     * @param \Illuminate\Database\Eloquent\Builder<TModelClass> $query
     *
     * @return mixed
     */
    public function __invoke(Builder $query, string $include)
    {
        $recipeQuery = app(abstract: RecipeQuery::class);

        $query->with(['recipeDetail' => fn($query) => $query->addSelect('recipe_uuid')]);
    }
}