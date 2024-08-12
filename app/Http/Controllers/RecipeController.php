<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Resources\Recipe\RecipeCollection;
use App\Http\Resources\Recipe\RecipeResource;
use App\Models\Recipe;
use App\Queries\RecipeQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RecipeQuery $query)
    {
        return RecipeCollection::make(
            $query->builder()->jsonPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request, RecipeQuery $query)
    {
        DB::beginTransaction();

        try {
            $recipe = new Recipe($request->input('data.attributes'));
            $recipe->user()->associate($request->user()->getKey());

            if ($request->hasRelationship('user')) {
                $data = $request->getRelationship('user');

                $recipe->user()->associate($data->get('id'));
            }

            $recipe->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        $recipe = $query->builder()
            ->where('uuid', $recipe->getKey())
            ->first();

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
    public function show(RecipeQuery $query, Recipe $recipe)
    {
        return RecipeResource::make(
            $query->builder()->first()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
