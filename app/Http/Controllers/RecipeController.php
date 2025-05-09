<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Resources\Recipe\RecipeCollection;
use App\Http\Resources\Recipe\RecipeResource;
use App\Models\Direction;
use App\Models\Recipe;
use App\Models\RecipeDetail;
use App\Queries\RecipeQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
    public function __construct(protected RecipeQuery $query) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->query->builder()->first();

        return $result;

        return RecipeCollection::make(
            $this->query->builder()->jsonPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request)
    {
        DB::beginTransaction();

        try {
            $recipe = Recipe::query()
                ->create([
                    ...$request->input('data.attributes'),
                    'user_uuid' => $request->user()->getKey(),
                ]);

            if ($request->hasRelationship('recipeDetail')) {
                $data = $request->getRelationship('recipeDetail');

                RecipeDetail::query()
                    ->where('uuid', $data->get('id'))
                    ->update(['recipe_uuid' => $recipe->getKey()]);
            }

            if ($request->hasRelationship('directions')) {
                $data = $request->getRelationship('directions');

                Direction::query()
                    ->whereIn('uuid', $data->pluck('id'))
                    ->update(['recipe_uuid' => $recipe->getKey()]);
            }

            if ($request->hasRelationship('ingredients')) {
                $data = $request->getRelationship('ingredients');

                $recipe->ingredients()->attach($data->pluck('id'));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        $recipe = $this->query
            ->builder()
            ->where($recipe->getKeyName(), $recipe->getKey())
            ->firstOrFail();

        return RecipeResource::make($recipe)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED)
            ->withHeaders([
                'Location' => route('recipes.show', $recipe->getKey()),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe = $this->query
            ->builder()
            ->where($recipe->getKeyName(), $recipe->getKey())
            ->firstOrFail();

        return RecipeResource::make($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        try {
            DB::beginTransaction();

            $recipe->update($request->input('data.attributes'));

            if ($request->hasRelationship('recipeDetail')) {
                $data = $request->getRelationship('recipeDetail');

                RecipeDetail::query()
                    ->where('uuid', $data->get('id'))
                    ->update(['recipe_uuid' => $recipe->getKey()]);
            }

            if ($request->hasRelationship('directions')) {
                $data = $request->getRelationship('directions');

                Direction::query()
                    ->whereIn('uuid', $data->pluck('id'))
                    ->update(['recipe_uuid' => $recipe->getKey()]);
            }

            if ($request->hasRelationship('ingredients')) {
                $data = $request->getRelationship('ingredients');

                $recipe->ingredients()->sync($data->pluck('id'));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        $recipe = $this->query
            ->builder()
            ->where($recipe->getKeyName(), $recipe->getKey())
            ->first();

        return RecipeResource::make($recipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
