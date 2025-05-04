<?php

namespace App\Http\Controllers;

use App\Http\Resources\RelationshipCollection;
use App\Http\Resources\RelationshipResource;
use App\Models\Recipe;
use App\Queries\RecipeQuery;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecipeRelationController extends Controller
{
    public function __construct(protected RecipeQuery $query) {}

    public function show(Recipe $recipe, string $relationName)
    {
        try {
            $query = $recipe->$relationName();
        } catch (Exception $e) {
            throw new NotFoundHttpException('Resource not found');
        }

        if ($relationName === Str::singular($relationName)) {
            $relation = $query->first();
        }

        if ($relationName === Str::plural($relationName)) {
            $relation = $query->jsonPaginate();
        }

        return $relation instanceof LengthAwarePaginator
            ? RelationshipCollection::make($relation)
            : RelationshipResource::make($relation);
    }

    public function updateUserRelation(Request $request, Recipe $recipe)
    {
        $recipe->update(['user_uuid' => $request->user()->getKey()]);

        $user = $recipe->user()->first();

        return RelationshipResource::make($user);
    }

    public function updateIngredientsRelation(Request $request, Recipe $recipe)
    {
        $data = $request->collect('data');

        $recipe->ingredients()->attach($data->pluck('id')->unique());

        $ingredients = $recipe->ingredients()->get();

        return RelationshipCollection::make($ingredients);
    }
}
